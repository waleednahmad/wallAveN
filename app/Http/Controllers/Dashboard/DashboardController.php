<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CartTemp;
use App\Models\Dealer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /** Orders that count as sales (not canceled or declined). */
    private const SALE_STATUSES = ['pending', 'processing', 'completed'];

    public function index()
    {
        $now = Carbon::now();
        $monthStart = $now->copy()->startOfMonth();
        $lastMonthStart = $monthStart->copy()->subMonthNoOverflow();
        // Same number of days into last month, for a fair comparison.
        $lastMonthSameDay = $lastMonthStart->copy()->addDays($now->day - 1)->setTimeFrom($now);
        if ($lastMonthSameDay->greaterThan($lastMonthStart->copy()->endOfMonth())) {
            $lastMonthSameDay = $lastMonthStart->copy()->endOfMonth();
        }

        $statusCounts = Order::selectRaw('status, COUNT(*) as n, SUM(total) as amount')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $salesThisMonth = (float) Order::whereIn('status', self::SALE_STATUSES)
            ->where('created_at', '>=', $monthStart)->sum('total');
        $ordersThisMonth = Order::whereIn('status', self::SALE_STATUSES)
            ->where('created_at', '>=', $monthStart)->count();
        $salesLastMonthToDate = (float) Order::whereIn('status', self::SALE_STATUSES)
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthSameDay])->sum('total');

        // Dealer applications not yet approved or rejected.
        $pendingDealersQuery = Dealer::where('is_approved', false)->whereNull('rejected_at');
        $pendingDealersCount = (clone $pendingDealersQuery)->count();
        $pendingDealers = $pendingDealersQuery->latest()->take(5)
            ->get(['id', 'name', 'company_name', 'email', 'city', 'state', 'created_at']);

        // Carts with items that haven't been checked out yet, grouped by dealer.
        $openCarts = CartTemp::whereNotNull('dealer_id')
            ->selectRaw('dealer_id, SUM(total) as amount, SUM(quantity) as items, MAX(updated_at) as last_activity')
            ->groupBy('dealer_id')
            ->get();

        $recentOrders = Order::with('dealer:id,name,company_name')
            ->latest()->take(8)
            ->get(['id', 'dealer_id', 'status', 'total', 'quantity', 'created_at']);

        $topDealers = Order::whereIn('status', self::SALE_STATUSES)
            ->where('created_at', '>=', $monthStart)
            ->selectRaw('dealer_id, COUNT(*) as orders, SUM(total) as amount')
            ->groupBy('dealer_id')
            ->orderByDesc('amount')
            ->take(5)
            ->with('dealer:id,name,company_name')
            ->get();

        return view('admin.index', [
            'statusCounts' => $statusCounts,
            'pendingOrders' => (int) ($statusCounts['pending']->n ?? 0),
            'pendingOrdersAmount' => (float) ($statusCounts['pending']->amount ?? 0),
            'processingOrders' => (int) ($statusCounts['processing']->n ?? 0),
            'salesThisMonth' => $salesThisMonth,
            'ordersThisMonth' => $ordersThisMonth,
            'salesLastMonthToDate' => $salesLastMonthToDate,
            'pendingDealersCount' => $pendingDealersCount,
            'pendingDealers' => $pendingDealers,
            'openCartsCount' => $openCarts->count(),
            'openCartsAmount' => (float) $openCarts->sum('amount'),
            'recentOrders' => $recentOrders,
            'topDealers' => $topDealers,
            'activeProducts' => Product::where('status', 1)->count(),
            'approvedDealers' => Dealer::where('is_approved', true)->count(),
            'monthLabel' => $now->format('F'),
        ]);
    }

    public function logout()
    {
        auth('web')->logout();
        return redirect()->route('frontend.home');
    }
}
