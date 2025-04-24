<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

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
        $order = Order::with('dealer', 'orderItems')->findOrFail($orderId);
        $address = $order->dealer->address ? explode(',', $order->dealer->address)[0] : '---';
        $city = $order->dealer->city ?? '---';
        $state = $order->dealer->state ?? '---';
        $zip_code = $order->dealer->zip_code ?? '---';
        $phone = $order->dealer->phone ?? '---';
        return view('prints.printOrder', compact('order', 'address', 'city', 'state', 'zip_code', 'phone'));
    }
}
