<?php

namespace App\Http\Controllers;

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
        $order = Order::with('dealer', 'orderItems')->findOrFail($orderId);
        $address = $order->dealer->address ? explode(',', $order->dealer->address)[0] : '---';
        $city = $order->dealer->city ?? '---';
        $state = $order->dealer->state ?? '---';
        $zip_code = $order->dealer->zip_code ?? '---';
        $phone = $order->dealer->phone ?? '---';
        return view('prints.printOrder', compact('order', 'address', 'city', 'state', 'zip_code', 'phone'));
    }
}
