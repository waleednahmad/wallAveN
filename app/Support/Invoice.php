<?php

namespace App\Support;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * The order invoice (resources/views/prints/invoice.blade.php) as a browser page or a PDF download.
 * Used by the admin and by dealers, so both get the same document.
 */
class Invoice
{
    public static function page(Order $order, ?string $pdfUrl = null, ?string $backUrl = null)
    {
        $order->loadMissing('dealer', 'orderItems');

        return view('prints.invoice', [
            'order' => $order,
            'forPdf' => false,
            'pdfUrl' => $pdfUrl,
            'backUrl' => $backUrl,
        ]);
    }

    public static function download(Order $order)
    {
        $order->loadMissing('dealer', 'orderItems');

        $pdf = Pdf::loadView('prints.invoice', [
            'order' => $order,
            'forPdf' => true,
            'logoImage' => self::logoDataUri(),
        ])
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Helvetica',
                'isRemoteEnabled' => false,   // images come from local files only
                'isPhpEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isFontSubsettingEnabled' => true,
                'dpi' => 150,
                // Product photos live in public/storage, a link to storage/app/public: allow both real folders
                'chroot' => [public_path(), storage_path('app/public')],
            ]);

        $name = Str::slug(getWebsiteTitle() ?: config('app.name')) . '-invoice-' . $order->id . '.pdf';

        return $pdf->download($name);
    }

    /** The logo embedded in the PDF (DomPDF reads local files; a data URI avoids path problems). */
    private static function logoDataUri(): ?string
    {
        $path = publicSetting('main logo');
        $file = $path ? public_path($path) : public_path('assets/img/logo.webp');
        if (!is_file($file)) {
            return null;
        }
        try {
            return 'data:' . mime_content_type($file) . ';base64,' . base64_encode(file_get_contents($file));
        } catch (\Throwable $e) {
            Log::warning('Invoice PDF: logo could not be read', ['path' => $file, 'error' => $e->getMessage()]);
            return null;
        }
    }
}
