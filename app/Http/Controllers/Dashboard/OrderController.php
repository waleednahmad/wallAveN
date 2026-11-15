<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Support\Invoice;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('admin.orders.index');
    }

    public function print($orderId)
    {
        $order = Order::findOrFail($orderId);

        return Invoice::page(
            $order,
            route('dashboard.orders.pdf', $order->id),
            route('dashboard.orders.index', ['order' => $order->id])
        );
    }

    public function pdf($orderId)
    {
        $order = Order::findOrFail($orderId);

        try {
            return Invoice::download($order);
        } catch (\Throwable $e) {
            Log::error('Failed to generate PDF for order', ['order_id' => $orderId, 'error' => $e->getMessage()]);

            return back()->with('error', 'The PDF could not be created. Please try again.');
        }
    }
}
