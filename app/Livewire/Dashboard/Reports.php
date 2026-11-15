<?php

namespace App\Livewire\Dashboard;

use App\Models\Dealer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Admin reports: sales for a period, monthly trend, top products and dealers,
 * where orders come from (dealer / sales rep / admin), sales rep performance, CSV export.
 * "Sales" = orders that are not canceled or declined.
 */
class Reports extends Component
{
    private const SALE_STATUSES = ['pending', 'processing', 'completed'];

    #[Url(except: 'month')]
    public string $range = 'month';

    #[Url(except: '')]
    public string $from = '';

    #[Url(except: '')]
    public string $to = '';

    /** Top dealers / top products: which column they are ranked by, and how many rows */
    public string $dealerSort = 'sales';
    public string $dealerDir = 'desc';
    public string $productSort = 'sales';
    public string $productDir = 'desc';
    public int $topLimit = 10;

    private const DEALER_SORTS = ['name' => 'name', 'orders' => 'orders', 'sales' => 'sales', 'avg' => 'avg_order', 'last' => 'last_order'];
    private const PRODUCT_SORTS = ['name' => 'order_items.name', 'units' => 'qty', 'orders' => 'orders', 'sales' => 'sales'];

    /** Click a header: same column flips the direction, a new column starts high-to-low (A–Z for names). */
    public function sortDealers(string $col): void
    {
        if (!isset(self::DEALER_SORTS[$col])) {
            return;
        }
        $this->dealerDir = $this->dealerSort === $col ? ($this->dealerDir === 'desc' ? 'asc' : 'desc') : ($col === 'name' ? 'asc' : 'desc');
        $this->dealerSort = $col;
    }

    public function sortProducts(string $col): void
    {
        if (!isset(self::PRODUCT_SORTS[$col])) {
            return;
        }
        $this->productDir = $this->productSort === $col ? ($this->productDir === 'desc' ? 'asc' : 'desc') : ($col === 'name' ? 'asc' : 'desc');
        $this->productSort = $col;
    }

    public function setTopLimit(int $n): void
    {
        $this->topLimit = in_array($n, [10, 25, 50], true) ? $n : 10;
    }

    public const RANGES = [
        'month' => 'This month',
        '30d' => 'Last 30 days',
        '90d' => 'Last 90 days',
        'year' => 'This year',
        '12m' => 'Last 12 months',
        'all' => 'All time',
        'custom' => 'Custom',
    ];

    public function setRange(string $range): void
    {
        if (array_key_exists($range, self::RANGES)) {
            $this->range = $range;
        }
    }

    /** @return array{0: ?Carbon, 1: Carbon} */
    private function period(): array
    {
        $now = Carbon::now();

        return match ($this->range) {
            'month' => [$now->copy()->startOfMonth(), $now],
            '30d' => [$now->copy()->subDays(30)->startOfDay(), $now],
            'year' => [$now->copy()->startOfYear(), $now],
            '12m' => [$now->copy()->subMonthsNoOverflow(12)->startOfDay(), $now],
            'all' => [null, $now],
            'custom' => [
                $this->from ? Carbon::parse($this->from)->startOfDay() : null,
                $this->to ? Carbon::parse($this->to)->endOfDay() : $now,
            ],
            default => [$now->copy()->subDays(90)->startOfDay(), $now],
        };
    }

    /** Sales orders (not canceled/declined) inside the selected period. */
    private function salesQuery()
    {
        [$start, $end] = $this->period();

        return Order::query()
            ->whereIn('orders.status', self::SALE_STATUSES)
            ->when($start, fn ($q) => $q->where('orders.created_at', '>=', $start))
            ->where('orders.created_at', '<=', $end);
    }

    public function exportCsv()
    {
        [$start, $end] = $this->period();
        $rows = $this->salesQuery()
            ->leftJoin('dealers', 'dealers.id', '=', 'orders.dealer_id')
            ->leftJoin('representatives', 'representatives.id', '=', 'orders.representative_id')
            ->orderBy('orders.created_at')
            ->get([
                'orders.id', 'orders.created_at', 'orders.status', 'orders.quantity', 'orders.total', 'orders.po_number',
                'dealers.company_name', 'dealers.email', 'representatives.name as rep_name', 'orders.admin_id',
            ]);

        $name = 'sales_' . ($start?->format('Y-m-d') ?? 'start') . '_to_' . $end->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Order', 'Date', 'Status', 'Dealer', 'Dealer email', 'Placed by', 'PO number', 'Items', 'Total']);
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->id,
                    Carbon::parse($r->created_at)->format('Y-m-d H:i'),
                    $r->status,
                    $r->company_name,
                    $r->email,
                    $r->rep_name ? 'Sales rep: ' . $r->rep_name : ($r->admin_id ? 'Admin' : 'Dealer'),
                    $r->po_number,
                    $r->quantity,
                    number_format((float) $r->total, 2, '.', ''),
                ]);
            }
            fclose($out);
        }, $name, ['Content-Type' => 'text/csv']);
    }

    public function render()
    {
        [$start, $end] = $this->period();

        $kpi = (clone $this->salesQuery())
            ->selectRaw('COUNT(*) as orders, COALESCE(SUM(total),0) as sales, COALESCE(SUM(quantity),0) as items, COUNT(DISTINCT dealer_id) as dealers')
            ->first();

        $completed = (float) (clone $this->salesQuery())->where('orders.status', 'completed')->sum('total');

        // Same length of time just before this period, to show whether things went up or down
        $previous = null;
        if ($start) {
            if ($this->range === 'month') {
                // 1st of last month up to the same day and time
                $prevStart = $start->copy()->subMonthNoOverflow();
                $prevEnd = $prevStart->copy()->addSeconds($start->diffInSeconds($end));
                $prevEnd = $prevEnd->min($prevStart->copy()->endOfMonth());
                $label = 'last month to the same day';
            } elseif ($this->range === 'year') {
                $prevStart = $start->copy()->subYear();
                $prevEnd = $end->copy()->subYear();
                $label = 'the same time last year';
            } else {
                $prevEnd = $start->copy();
                $prevStart = $start->copy()->subSeconds($start->diffInSeconds($end));
                $label = 'the ' . max(1, (int) floor($prevStart->diffInDays($prevEnd))) . ' days before';
            }
            $previous = Order::query()
                ->whereIn('status', self::SALE_STATUSES)
                ->where('created_at', '>=', $prevStart)
                ->where('created_at', '<', $prevEnd)
                ->selectRaw('COUNT(*) as orders, COALESCE(SUM(total),0) as sales')
                ->first();
            $previous->label = $label;
        }
        $change = fn ($now, $before) => $before > 0 ? round(($now - $before) / $before * 100) : null;

        $newDealers = Dealer::where('is_approved', true)
            ->when($start, fn ($q) => $q->where('approved_at', '>=', $start))
            ->where('approved_at', '<=', $end)
            ->count();

        // Monthly trend: always the last 12 months, so the chart has context whatever the period
        $monthsStart = Carbon::now()->startOfMonth()->subMonthsNoOverflow(11);
        $monthly = Order::whereIn('status', self::SALE_STATUSES)
            ->where('created_at', '>=', $monthsStart)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as orders, SUM(total) as sales")
            ->groupBy('ym')
            ->pluck('sales', 'ym');
        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $m = $monthsStart->copy()->addMonthsNoOverflow($i);
            $months[] = ['label' => $m->format('M'), 'year' => $m->format('Y'), 'sales' => (float) ($monthly[$m->format('Y-m')] ?? 0)];
        }
        $maxMonth = max(1, max(array_column($months, 'sales')));

        $dealerCol = self::DEALER_SORTS[$this->dealerSort] ?? 'sales';
        $dealerDir = $this->dealerDir === 'asc' ? 'asc' : 'desc';
        $topDealers = (clone $this->salesQuery())
            ->join('dealers', 'dealers.id', '=', 'orders.dealer_id')
            ->groupBy('orders.dealer_id', 'dealers.company_name', 'dealers.name')
            ->selectRaw("orders.dealer_id, COALESCE(NULLIF(dealers.company_name, ''), dealers.name) as name, COUNT(*) as orders, SUM(orders.total) as sales, SUM(orders.total) / COUNT(*) as avg_order, MAX(orders.created_at) as last_order")
            ->orderBy($dealerCol, $dealerDir)
            ->orderByDesc('sales')
            ->take($this->topLimit)
            ->get();

        $productCol = self::PRODUCT_SORTS[$this->productSort] ?? 'sales';
        $productDir = $this->productDir === 'asc' ? 'asc' : 'desc';
        $topProducts = OrderItem::query()
            ->joinSub($this->salesQuery()->select('orders.id'), 'o', 'o.id', '=', 'order_items.order_id')
            ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
            ->groupBy('order_items.product_id', 'order_items.name')
            ->selectRaw('order_items.product_id, order_items.name, SUM(order_items.quantity) as qty, COUNT(DISTINCT order_items.order_id) as orders, SUM(order_items.total) as sales, MAX(products.image) as image, MAX(products.deleted_at) as deleted_at')
            ->orderBy($productCol, $productDir)
            ->orderByDesc('sales')
            ->take($this->topLimit)
            ->get();

        $channels = (clone $this->salesQuery())
            ->selectRaw("CASE WHEN representative_id IS NOT NULL THEN 'Sales reps' WHEN admin_id IS NOT NULL THEN 'Admins' ELSE 'Dealers directly' END as channel, COUNT(*) as orders, SUM(total) as sales")
            ->groupBy('channel')
            ->orderByDesc('sales')
            ->get();

        $reps = (clone $this->salesQuery())
            ->join('representatives', 'representatives.id', '=', 'orders.representative_id')
            ->groupBy('orders.representative_id', 'representatives.name')
            ->selectRaw('representatives.name, COUNT(*) as orders, SUM(orders.total) as sales, COUNT(DISTINCT orders.dealer_id) as dealers')
            ->orderByDesc('sales')
            ->get();

        $statusMix = Order::query()
            ->when($start, fn ($q) => $q->where('created_at', '>=', $start))
            ->where('created_at', '<=', $end)
            ->selectRaw('status, COUNT(*) as n, SUM(total) as amount')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        return view('livewire.dashboard.reports', [
            'ranges' => self::RANGES,
            'periodLabel' => ($start ? $start->format('M j, Y') : 'The beginning') . ' – ' . $end->format('M j, Y'),
            'kpi' => $kpi,
            'previous' => $previous,
            'salesChange' => $previous ? $change((float) $kpi->sales, (float) $previous->sales) : null,
            'ordersChange' => $previous ? $change((int) $kpi->orders, (int) $previous->orders) : null,
            'avgOrder' => $kpi->orders ? $kpi->sales / $kpi->orders : 0,
            'completed' => $completed,
            'newDealers' => $newDealers,
            'months' => $months,
            'maxMonth' => $maxMonth,
            'topDealers' => $topDealers,
            'topProducts' => $topProducts,
            'channels' => $channels,
            'reps' => $reps,
            'statusMix' => $statusMix,
        ]);
    }
}
