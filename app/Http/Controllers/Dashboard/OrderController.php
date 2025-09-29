<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\PublicSetting;
use Barryvdh\DomPDF\Facade\Pdf;
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
        try {
            $order = Order::with(['dealer', 'orderItems'])->findOrFail($orderId);
            $dealer = $order->dealer;
            $address = $dealer && $dealer->address ? explode(',', $dealer->address)[0] : '---';
            $city = $dealer->city ?? '---';
            $state = $dealer->state ?? '---';
            $zip_code = $dealer->zip_code ?? '---';
            $phone = $dealer->phone ?? '---';

            // Get logo image with proper path handling for DomPDF
            $logoSetting = PublicSetting::where('key', 'main logo')->first();
            $logoImage = null;
            
            if ($logoSetting && $logoSetting->value) {
                // Convert to base64 for better DomPDF compatibility
                $logoPath = public_path($logoSetting->value);
                
                if (file_exists($logoPath)) {
                    try {
                        $logoData = base64_encode(file_get_contents($logoPath));
                        $logoMimeType = mime_content_type($logoPath);
                        $logoImage = 'data:' . $logoMimeType . ';base64,' . $logoData;
                        
                        Log::info('Logo loaded successfully for PDF', ['path' => $logoPath]);
                    } catch (\Exception $logoException) {
                        Log::warning('Failed to load logo for PDF', [
                            'path' => $logoPath,
                            'error' => $logoException->getMessage()
                        ]);
                        // Continue without logo
                    }
                } else {
                    Log::warning('Logo file not found for PDF', ['path' => $logoPath]);
                }
            }

            // Determine which template to use (you can add a config option for this)
            $template = config('app.pdf_template', 'prints.order'); // Default to main template
            
            // Generate PDF with DomPDF optimized settings
            $pdf = Pdf::loadView($template, compact('order', 'address', 'city', 'state', 'zip_code', 'phone', 'logoImage'))
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'defaultFont' => 'Arial',
                    'isRemoteEnabled' => false,
                    'isHtml5ParserEnabled' => true,
                    'isFontSubsettingEnabled' => true,
                    'dpi' => 150,
                    'chroot' => public_path(),
                ]);
                
            Log::info('PDF generated successfully for order', ['order_id' => $orderId, 'template' => $template]);
            return $pdf->download('order-' . $order->id . '.pdf');
            
        } catch (\Exception $e) {
            Log::error('Failed to generate PDF for order', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return a user-friendly error response
            return back()->withErrors(['pdf' => 'Unable to generate PDF. Please try again or contact support.']);
        }
    }
}
