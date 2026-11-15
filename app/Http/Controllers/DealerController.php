<?php

namespace App\Http\Controllers;

use App\Support\Invoice;

use App\Models\Order;
use Illuminate\Http\Request;

class DealerController extends Controller
{
    public function dashboard()
    {
        $ordersCountByStatus = auth('dealer')->user()->orders()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
        return view('dealer.dashboard', compact('ordersCountByStatus'));
    }

    public function profile()
    {
        return view('dealer.profile');
    }

    public function orders()
    {
        return view('dealer.orders');
    }

    public function customerMode()
    {
        return view('dealer.customer-mode');
    }

    public function print($orderId)
    {
        // Only the dealer who placed the order may see it
        $order = auth('dealer')->user()->orders()->findOrFail($orderId);

        return Invoice::page($order, route('dealer.orders.pdf', $order->id), route('dealer.orders'));
    }

    public function pdf($orderId)
    {
        $order = auth('dealer')->user()->orders()->findOrFail($orderId);

        try {
            return Invoice::download($order);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to generate invoice PDF for dealer', ['order_id' => $orderId, 'error' => $e->getMessage()]);

            return back()->with('error', 'The PDF could not be created. Please try again.');
        }
    }
}
