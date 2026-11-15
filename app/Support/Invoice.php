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

    /**
     * Small JPEG copy of a product photo for the PDF, made once and reused.
     * DomPDF stores WebP/PNG decoded (a 100-line order came to ~5 MB and ~9 s); JPEG goes in as it is.
     * Returns an absolute path inside the allowed folders, or null.
     */
    public static function pdfImage(?string $path, int $size = 96): ?string
    {
        if (!$path) {
            return null;
        }
        $src = public_path(ltrim($path, '/'));
        if (!is_file($src)) {
            return null;
        }

        $dir = storage_path('app/public/invoice-thumbs');
        $out = $dir . '/' . md5($path . '|' . filemtime($src) . '|' . $size) . '.jpg';
        if (is_file($out)) {
            return $out;
        }

        try {
            $im = @imagecreatefromstring((string) file_get_contents($src));
            if (!$im) {
                return $src;
            }
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            // Square crop from the centre, on white (JPEG has no transparency)
            $w = imagesx($im);
            $h = imagesy($im);
            $side = min($w, $h);
            $thumb = imagecreatetruecolor($size, $size);
            imagefill($thumb, 0, 0, imagecolorallocate($thumb, 255, 255, 255));
            imagecopyresampled($thumb, $im, 0, 0, (int) (($w - $side) / 2), (int) (($h - $side) / 2), $size, $size, $side, $side);
            imagejpeg($thumb, $out, 82);
            imagedestroy($im);
            imagedestroy($thumb);

            return is_file($out) ? $out : $src;
        } catch (\Throwable $e) {
            Log::warning('Invoice PDF: thumbnail failed', ['path' => $path, 'error' => $e->getMessage()]);
            return $src;
        }
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
