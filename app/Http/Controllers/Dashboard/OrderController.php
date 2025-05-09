<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\PublicSetting;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function pdf($orderId)
    {
        $order = Order::with(['dealer', 'orderItems'])->findOrFail($orderId);
        $dealer = $order->dealer;
        $address = $dealer && $dealer->address ? explode(',', $dealer->address)[0] : '---';
        $city = $dealer->city ?? '---';
        $state = $dealer->state ?? '---';
        $zip_code = $dealer->zip_code ?? '---';
        $phone = $dealer->phone ?? '---';

        // Pass the public path for images to the view
        $logoImage  = PublicSetting::where('key', 'main logo')->first()->value;
        $logoImage = public_path($logoImage);
        $pdf = Pdf::loadView('prints.order', compact('order', 'address', 'city', 'state', 'zip_code', 'phone', 'logoImage'));
        return $pdf->download('order-' . $order->id . '.pdf');
    }
}
