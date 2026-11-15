<?php

namespace App\Livewire\Tables;

use App\Models\Dealer;
use App\Models\Order;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;
use App\Mail\OrderStatusUpdated;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class OrderTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'order-table-jxuh87-table';
    public $fileName = '';

    /** Status tab above the table (?status=pending). 'all' shows every order. */
    // Newest first by default; clicking a column header sorts by that column instead
    public string $sortField = 'orders.created_at';
    public string $sortDirection = 'desc';

    #[Url(as: 'status', except: 'all')]
    public string $statusTab = 'all';

    /** Statuses an admin can set in bulk (same as the single-order modal; 'canceled' is the dealer's own action). */
    private const BULK_STATUSES = ['processing', 'completed', 'declined'];
    private const TABS = ['all', 'pending', 'processing', 'completed', 'declined', 'canceled'];


    public function setUp(): array
    {
        $this->showCheckBox();
        $this->fileName = 'orders_' . Carbon::now()->format('Y-m-d_H-i-s');

        return [
            PowerGrid::exportable(fileName: $this->fileName)
                ->type(Exportable::TYPE_XLS)
                ->columnWidth([
                    1 => 20,
                    2 => 35,
                    3 => 20,
                    4 => 20,
                    5 => 20,
                    6 => 20,
                    7 => 20,
                    8 => 30,
                    9 => 20,
                    10 => 20,
                ]),
            PowerGrid::header()
                ->showSearchInput()
                ->includeViewOnTop('livewire.tables.partials.order-status-bar'),

            PowerGrid::footer()
                ->showPerPage(
                    $perPage = 25,
                    $perPageValues = [10, 25, 50, 100, 0]
                )
                ->showRecordCount(),
        ];
    }


    #[On('refreshOrders')]
    public function datasource(): Builder
    {
        return Order::join('dealers', 'orders.dealer_id', '=', 'dealers.id')
            ->leftJoin('representatives', 'orders.representative_id', '=', 'representatives.id')
            ->select([
                'orders.id',
                'orders.status',
                'orders.total',
                'orders.created_at',
                'orders.quantity',
                'dealers.company_name as company_name',
                'dealers.email as dealer_email',
                'dealers.phone as dealer_phone',
                'orders.po_number',
                'orders.admin_id',
                'orders.representative_id',
                'representatives.name as rep_name',
            ])
            ->when($this->statusTab !== 'all', fn ($query) => $query->where('orders.status', $this->statusTab));
    }

    /** Order counts per status for the tabs (same join as the table, so the numbers match). */
    #[Computed]
    public function statusCounts(): array
    {
        return Order::join('dealers', 'orders.dealer_id', '=', 'dealers.id')
            ->selectRaw('orders.status as status, COUNT(*) as n')
            ->groupBy('orders.status')
            ->pluck('n', 'status')
            ->map(fn ($n) => (int) $n)
            ->all();
    }

    public function setStatusTab(string $tab): void
    {
        if (!in_array($tab, self::TABS, true)) {
            return;
        }
        $this->statusTab = $tab;
        $this->clearSelection();
        $this->resetPage();
    }

    public function clearSelection(): void
    {
        $this->checkboxValues = [];
        $this->checkboxAll = false;
        $this->dispatch('pgBulkActions::clear', $this->tableName);
    }

    /**
     * Change the status of the selected orders at once. Orders the dealer canceled are left alone.
     * Dealers are emailed only when $notify is true (the single-order modal always emails).
     */
    public function bulkStatus(string $status, array $ids, bool $notify = false): void
    {
        if (!in_array($status, self::BULK_STATUSES, true)) {
            $this->dispatch('error', 'Unknown status');
            return;
        }
        $ids = array_values(array_filter(array_map('intval', $ids)));
        if (!$ids) {
            $this->dispatch('error', 'Select at least one order');
            return;
        }

        $orders = Order::with('dealer')
            ->whereIn('id', $ids)
            ->where('status', '!=', 'canceled')
            ->get();

        foreach ($orders as $order) {
            $order->update(['status' => $status]);
            if ($notify && $order->dealer?->email) {
                try {
                    Mail::to($order->dealer->email)->send(new OrderStatusUpdated($order));
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        }

        $skipped = count($ids) - $orders->count();
        $message = $orders->count() . ' ' . ($orders->count() === 1 ? 'order' : 'orders') . ' marked ' . $status
            . ($skipped ? " ({$skipped} canceled by the dealer, left as is)" : '')
            . ($notify ? ' · dealers emailed' : '');

        unset($this->statusCounts);
        $this->clearSelection();
        $this->dispatch('success', $message);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            // Plain fields are escaped by PowerGrid; columns not listed here would print raw HTML
            ->add('po_number')
            ->add('dealer_email')
            ->add('dealer_phone')
            ->add('quantity')
            ->add('id')
            // ->add('company_name', function (Order $order) {
            //     return $order->dealer ? $order->dealer->company_name : '';
            // })
            // ->add('dealer_email', function (Order $order) {
            //     return $order->dealer ? $order->dealer->email : '';
            // })
            // ->add('dealer_phone', function (Order $order) {
            //     return $order->dealer ? $order->dealer->phone : '';
            // })

            // Company with email and phone under it, so the table fits the screen. The column keeps
            // its field name, so the Company filter, search and sorting work as before.
            ->add('company_name', function (Order $order) {
                $sub = array_map(fn ($v) => '<span class="adm-nowrap">' . e($v) . '</span>', array_filter([$order->dealer_email, $order->dealer_phone]));
                return '<div class="adm-cell-main">' . e($order->company_name ?: '-') . '</div>'
                    . ($sub ? '<div class="adm-cell-sub">' . implode(' · ', $sub) . '</div>' : '');
            })
            ->add('company_name_plain', fn (Order $order) => $order->company_name)
            ->add('status', function (Order $order) {
                switch ($order->status) {
                    case 'pending':
                        return "<span class='badge badge-warning'>Pending</span>";
                    case 'processing':
                        return "<span class='badge badge-info'>Processing</span>";
                    case 'completed':
                        return "<span class='badge badge-success'>Completed</span>";
                    case 'declined':
                        return "<span class='badge badge-danger'>Declined</span>";
                    case 'canceled':
                        return "<span class='badge badge-danger'>Canceled</span>";
                    default:
                        return "<span class='badge badge-secondary'>Unknown</span>";
                }
            })

            ->add('status_label', function (Order $order) {
                return $order->status;
            })

            ->add('total', function (Order $order) {
                return "$" . number_format($order->total, 2);
            })

            ->add('created_at_formatted', function (Order $order) {
                return $order->created_at->format('Y-m-d H:i:s');
            })


            // Display copy under its own name: assigning text to created_at itself gets cast back to a date
            ->add('created_at')
            ->add('placed_by', fn (Order $order) => $order->representative_id
                ? '<span class="adm-cell-main">' . e($order->rep_name ?: 'Sales rep') . '</span><div class="adm-cell-sub">Sales rep</div>'
                : ($order->admin_id ? '<span class="text-muted">Admin</span>' : '<span class="text-muted">Dealer</span>'))
            ->add('placed_by_label', fn (Order $order) => $order->representative_id ? 'Sales rep: ' . $order->rep_name : ($order->admin_id ? 'Admin' : 'Dealer'))
            ->add('placed_on', fn (Order $order) => $order->created_at?->format('M j, Y · g:i A'));
    }

    public function columns(): array
    {
        return [
            Column::make('Order', 'id')->sortable()->searchable(),

            Column::make('PO number', 'po_number', 'orders.po_number')
                ->searchable()
                ->hidden()
                ->visibleInExport(true),

            Column::make('Dealer', 'company_name', 'dealers.company_name')->bodyAttribute('gr-wrap')
                ->searchable()
                ->sortable()
                ->visibleInExport(false),

            // Plain-text copies for the export; email and phone are also searched while hidden.
            Column::make('Company Name', 'company_name_plain', 'dealers.company_name')
                ->hidden()
                ->visibleInExport(true),

            Column::make('Dealer Email', 'dealer_email', 'dealers.email')
                ->searchable()
                ->hidden()
                ->visibleInExport(true),

            Column::make('Dealer Phone', 'dealer_phone', 'dealers.phone')
                ->searchable()
                ->hidden()
                ->visibleInExport(true),

            Column::make('Status', 'status')
                ->searchable()
                ->visibleInExport(false),

            Column::make('Status', 'status_label')
                ->hidden()
                ->visibleInExport(true),

            Column::make('Placed by', 'placed_by', 'orders.representative_id')->bodyAttribute('gr-nowrap')
                ->visibleInExport(false),
            Column::make('Placed by', 'placed_by_label')
                ->hidden()
                ->visibleInExport(true),

            Column::make('Total', 'total')->headerAttribute('text-end')->bodyAttribute('gr-nowrap text-end')
                ->withSum('total', header: false, footer: true)
                ->sortable()
                ->searchable(),

            Column::make('Items', 'quantity')->headerAttribute('text-end')->bodyAttribute('text-end')
                ->withSum('quantity', header: false, footer: true)
                ->sortable(),

            Column::make('Date', 'placed_on', 'orders.created_at')->bodyAttribute('gr-nowrap')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Created at', 'created_at_formatted', 'created_at')->bodyAttribute('gr-nowrap')
                ->searchable()
                ->hidden()
                ->visibleInExport(true),

            Column::action('Action')
        ];
    }

    public function summarizeFormat(): array
    {
        return [
            'total.{sum,avg,count,min,max}' => fn($value) => "$" . number_format($value, 2),
            'quantity.{sum,avg,count,min,max}' => fn($value) => number_format($value),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('placed_on', 'orders.created_at'),

            Filter::select('placed_by', 'placed_by')
                ->dataSource([
                    ['value' => 'dealer', 'label' => 'Dealer (online)'],
                    ['value' => 'rep', 'label' => 'Sales rep'],
                    ['value' => 'admin', 'label' => 'Admin'],
                ])
                ->optionValue('value')
                ->optionLabel('label')
                ->builder(fn (Builder $query, $value) => match ($value) {
                    'dealer' => $query->whereNull('orders.representative_id')->whereNull('orders.admin_id'),
                    'rep' => $query->whereNotNull('orders.representative_id'),
                    'admin' => $query->whereNull('orders.representative_id')->whereNotNull('orders.admin_id'),
                    default => $query,
                }),

            // Status is picked with the tabs above the table, so it has no filter here

            Filter::select('company_name', 'company_name')
                ->dataSource(
                    Dealer::whereHas('orders')
                        ->orderBy('company_name')
                        ->get(['id', 'company_name'])
                        ->map(function ($dealer) {
                            return [
                                'label' => $dealer->company_name,
                                'value' => $dealer->company_name,
                            ];
                        })->toArray()
                )
                ->optionLabel('label')
                ->optionValue('value'),
        ];
    }

    #[\Livewire\Attributes\On('show')]
    public function show($rowId): void
    {
        // Order details + status changes now live in the side panel (OrderDrawer)
        $this->dispatch('openOrderDrawer', orderId: $rowId);
    }

    #[\Livewire\Attributes\On('editStatus')]
    public function editStatus($rowId): void
    {
        $this->dispatch('showChangeStatusModal', ['order' => $rowId]);
    }

    public function actions(Order $row): array
    {
        return [
            Button::make('show')
                ->slot('<i class="fas fa-eye"></i> <span>Open</span>')
                ->class('btn btn-info btn-sm rounded')
                ->dispatch('show', ['rowId' => $row->id]),


            Button::make('print')
                ->slot('<i class="fas fa-print"></i>')
                ->attributes(['title' => 'Print invoice', 'aria-label' => 'Print invoice'])
                ->class('btn btn-secondary btn-sm rounded')
                ->route('dashboard.orders.print', ['order' => $row->id], '_blank'),

            // New PDF action button
            Button::make('pdf')
                ->slot('<i class="fas fa-file-pdf"></i>')
                ->attributes(['title' => 'Download PDF', 'aria-label' => 'Download PDF'])
                ->class('btn btn-danger btn-sm rounded')
                ->route('dashboard.orders.pdf', ['order' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            // Hide button edit for ID 1
            Rule::button('editStatus')
                ->when(fn($row) => $row->status == 'completed' || $row->status == 'declined' || $row->status == 'canceled')
                ->hide(),
        ];
    }
}
