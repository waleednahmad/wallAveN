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

        // Force file download with headers
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'order-' . $order->id . '.pdf', [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="order-' . $order->id . '.pdf"',
        ]);
    }

    // public function pdf($orderId)
    // {
    //     try {
    //         ob_clean(); // Clean output buffer

    //         $order = Order::with(['dealer', 'orderItems'])->findOrFail($orderId);
    //         $dealer = $order->dealer;
    //         $address = $dealer && $dealer->address ? explode(',', $dealer->address)[0] : '---';
    //         $city = $dealer->city ?? '---';
    //         $state = $dealer->state ?? '---';
    //         $zip_code = $dealer->zip_code ?? '---';
    //         $phone = $dealer->phone ?? '---';

    //         // Use asset() for logo URL
    //         $logoImage = asset(PublicSetting::where('key', 'main logo')->first()->value);

    //         // Log for debugging
    //         Log::info('Generating PDF for order', [
    //             'order_id' => $orderId,
    //             'logo_url' => $logoImage,
    //         ]);

    //         $pdf = Pdf::loadView('prints.order', compact('order', 'address', 'city', 'state', 'zip_code', 'phone', 'logoImage'))
    //             ->setOptions([
    //                 'isHtml5ParserEnabled' => true,
    //                 'isRemoteEnabled' => true, // Required for asset() URLs
    //                 'dpi' => 150,
    //                 'defaultFont' => 'sans-serif',
    //             ]);

    //         // Stream for testing
    //         return $pdf->stream('order-' . $order->id . '.pdf');
    //         // Switch to download once confirmed
    //         // return $pdf->download('order-' . $order->id . '.pdf');

    //     } catch (\Exception $e) {
    //         Log::error('PDF generation failed', [
    //             'order_id' => $orderId,
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString(),
    //         ]);

    //         return response()->json([
    //             'error' => 'Failed to generate PDF: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }
}
