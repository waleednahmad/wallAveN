<?php

namespace App\Jobs;

use App\Models\Catalog;
use App\Models\Product;
use App\Models\PublicSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GenerateCatalogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** Long timeout — 30 minutes — for big catalogs. */
    public int $timeout = 1800;

    /** Don't auto-retry — failures should be visible immediately. */
    public int $tries = 1;

    /** Max width/height for product images embedded in the PDF (px). */
    private const PRODUCT_IMAGE_MAX = 600;

    /** Max width/height for cover images (px). */
    private const COVER_IMAGE_MAX = 1600;

    /** JPEG quality for downscaled images. */
    private const JPEG_QUALITY = 80;

    public function __construct(public int $catalogId)
    {
    }

    public function handle(): void
    {
        // Raise memory limit and execution time at runtime — the PHP CLI
        // default of 128M is far too small for mPDF + many product images.
        @ini_set('memory_limit', '1024M');
        @set_time_limit(0);

        $catalog = Catalog::find($this->catalogId);
        if (!$catalog) {
            return;
        }

        $catalog->update([
            'status' => Catalog::STATUS_PROCESSING,
            'started_at' => now(),
            'error_message' => null,
        ]);

        // Ensure mpdf temp dir exists
        $mpdfTempDir = storage_path('app/mpdf-temp');
        if (!is_dir($mpdfTempDir)) {
            @mkdir($mpdfTempDir, 0775, true);
        }

        $tempFiles = [];
        $productsCount = 0;
        $absoluteOutput = null;

        try {
            // Cover images — downscale aggressively
            $frontCoverAbs = $catalog->front_cover_path
                ? $this->prepareImage(Storage::disk('local')->path($catalog->front_cover_path), self::COVER_IMAGE_MAX, $tempFiles)
                : null;

            $backCoverAbs = $catalog->back_cover_path
                ? $this->prepareImage(Storage::disk('local')->path($catalog->back_cover_path), self::COVER_IMAGE_MAX, $tempFiles)
                : null;

            // Logo
            $logoSetting = PublicSetting::where('key', 'main logo')->first()?->value ?? 'assets/img/logo.webp';
            $logoPath = $this->prepareImage(public_path($logoSetting), 400, $tempFiles);

            // Layout
            $layout = $catalog->layout;
            [$cols, $rows] = $layout === '3x3' ? [3, 3] : [2, 3];
            $perPage = $cols * $rows;

            // Build the product query — DON'T fetch yet, we'll chunk it
            $query = $this->buildProductQuery($catalog);

            // Pre-count for catalog row
            $productsCount = (clone $query)->count();

            // Build mPDF — A4 with tight, balanced margins.
            // top 18 / bottom 12 leave room for the running header/footer
            // (margin_header 4, margin_footer 4) so they never overlap cards.
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'default_font_size' => 9,
                'default_font' => 'sans-serif',
                'margin_left' => 8,
                'margin_right' => 8,
                'margin_top' => 18,
                'margin_bottom' => 12,
                'margin_header' => 4,
                'margin_footer' => 4,
                'tempDir' => $mpdfTempDir,
            ]);

            // Reduce mPDF memory pressure / noise
            $mpdf->showImageErrors = false;
            $mpdf->SetTitle($catalog->name ?: 'Product Catalog');

            // Parse stylesheet in HEADER_CSS mode so mPDF treats it as
            // pure styles and does NOT auto-open a blank first page.
            $css = view('admin.catalog.pdf-styles', ['layout' => $layout])->render();
            // Strip <style> tags — HEADER_CSS expects raw CSS, not HTML.
            $css = preg_replace('#</?style[^>]*>#i', '', $css);
            $mpdf->WriteHTML(trim($css), \Mpdf\HTMLParserMode::HEADER_CSS);

            // Pre-render header & footer HTML once
            $headerHtml = view('admin.catalog.pdf-header', ['logoPath' => $logoPath])->render();
            $footerHtml = view('admin.catalog.pdf-footer', ['footerText' => $catalog->footer_text ?? ''])->render();

            // Front cover — full-bleed, no header/footer, no margins.
            if ($frontCoverAbs) {
                $mpdf->SetHTMLHeader('');
                $mpdf->SetHTMLFooter('');
                $mpdf->AddPageByArray([
                    'margin-left' => 0, 'margin-right' => 0,
                    'margin-top' => 0,  'margin-bottom' => 0,
                    'margin-header' => 0, 'margin-footer' => 0,
                    'odd-header-name' => '', 'odd-footer-name' => '',
                    'newformat' => 'A4',
                ]);
                $coverHtml = view('admin.catalog.pdf-cover', ['imagePath' => $frontCoverAbs])->render();
                $mpdf->WriteHTML($coverHtml, \Mpdf\HTMLParserMode::HTML_BODY);
            }

            // Switch back to product-page geometry + running header/footer.
            // AddPageByArray here resets margins so the first product page has them.
            $mpdf->SetHTMLHeader($headerHtml);
            $mpdf->SetHTMLFooter($footerHtml);
            $mpdf->AddPageByArray([
                'margin-left' => 8, 'margin-right' => 8,
                'margin-top' => 18, 'margin-bottom' => 12,
                'margin-header' => 4, 'margin-footer' => 4,
                'newformat' => 'A4',
                'resetpagenum' => 1,
            ]);

            // ---- Stream products in DB chunks, page-by-page ----
            // We accumulate $perPage products in $buffer, render a page, then
            // delete every temp image used by that page before moving on.
            $buffer = [];
            $pageTempFiles = [];
            $pageIndex = 0;
            $isFirstProductPage = true;
            $globalIndex = 0; // running 1-based index across all pages (for №N badge)

            // Use lazy() so Eloquent reads in cursor-style chunks of 50 rows
            // and never holds the whole result set in memory.
            $query->lazy(50)->each(function ($product) use (
                &$buffer, &$pageTempFiles, &$pageIndex, &$isFirstProductPage, &$globalIndex,
                $mpdf, $cols, $rows, $perPage, $layout, $productsCount
            ) {
                $buffer[] = $this->mapProduct($product, $pageTempFiles);

                if (count($buffer) >= $perPage) {
                    $startIndex = $globalIndex + 1;
                    $this->renderPage($mpdf, $buffer, $cols, $rows, $layout, $pageIndex, $isFirstProductPage, $startIndex, $productsCount);
                    $this->cleanupTemp($pageTempFiles);
                    $globalIndex += count($buffer);
                    $buffer = [];
                    $pageTempFiles = [];
                    $pageIndex++;
                    $isFirstProductPage = false;
                    gc_collect_cycles();
                }
            });

            // Flush remainder (last partial page)
            if (count($buffer) > 0) {
                $startIndex = $globalIndex + 1;
                $this->renderPage($mpdf, $buffer, $cols, $rows, $layout, $pageIndex, $isFirstProductPage, $startIndex, $productsCount);
                $this->cleanupTemp($pageTempFiles);
                $globalIndex += count($buffer);
                $buffer = [];
                $pageTempFiles = [];
                gc_collect_cycles();
            }

            // Back cover — full-bleed, no header/footer/margins.
            if ($backCoverAbs) {
                $mpdf->SetHTMLHeader('');
                $mpdf->SetHTMLFooter('');
                $mpdf->AddPageByArray([
                    'margin-left' => 0, 'margin-right' => 0,
                    'margin-top' => 0,  'margin-bottom' => 0,
                    'margin-header' => 0, 'margin-footer' => 0,
                    'odd-header-name' => '', 'odd-footer-name' => '',
                    'newformat' => 'A4',
                ]);
                $coverHtml = view('admin.catalog.pdf-cover', ['imagePath' => $backCoverAbs])->render();
                $mpdf->WriteHTML($coverHtml, \Mpdf\HTMLParserMode::HTML_BODY);
            }

            // Save PDF
            $filename = 'catalogs/catalog-' . $catalog->id . '-' . now()->format('Ymd-His') . '.pdf';
            $absoluteOutput = Storage::disk('local')->path($filename);
            if (!is_dir(dirname($absoluteOutput))) {
                @mkdir(dirname($absoluteOutput), 0775, true);
            }
            $mpdf->Output($absoluteOutput, \Mpdf\Output\Destination::FILE);

            // Free mPDF
            unset($mpdf);
            gc_collect_cycles();

            $catalog->update([
                'status' => Catalog::STATUS_COMPLETED,
                'completed_at' => now(),
                'file_path' => $filename,
                'products_count' => $productsCount,
                'file_size' => @filesize($absoluteOutput) ?: null,
            ]);
        } catch (Throwable $e) {
            Log::error('Catalog generation failed', [
                'catalog_id' => $catalog->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $catalog->update([
                'status' => Catalog::STATUS_FAILED,
                'error_message' => $e->getMessage(),
                'completed_at' => now(),
            ]);
        } finally {
            $this->cleanupTemp($tempFiles);
        }
    }

    public function failed(Throwable $exception): void
    {
        $catalog = Catalog::find($this->catalogId);
        if ($catalog && $catalog->status !== Catalog::STATUS_COMPLETED) {
            $catalog->update([
                'status' => Catalog::STATUS_FAILED,
                'error_message' => $exception->getMessage(),
                'completed_at' => now(),
            ]);
        }
    }

    private function buildProductQuery(Catalog $catalog)
    {
        $query = Product::active()
            ->with(['variants.attributeValues.attribute'])
            ->orderBy('name');

        if ($catalog->category_id) {
            $query->whereHas('categories', function ($q) use ($catalog) {
                $q->where('categories.id', $catalog->category_id);
            });
        }

        $subIds = $catalog->subcategory_ids ?? [];
        if (!empty($subIds)) {
            $query->whereHas('subCategories', function ($q) use ($subIds) {
                $q->whereIn('sub_categories.id', $subIds);
            });
        }

        return $query;
    }

    private function mapProduct($product, array &$tempFiles): array
    {
        $groupedAttributes = [];
        foreach ($product->variants as $variant) {
            foreach ($variant->attributeValues as $attributeValue) {
                $attrName = $attributeValue->attribute->name ?? null;
                $value = $attributeValue->value;
                if (!$attrName) continue;
                if (!isset($groupedAttributes[$attrName])) {
                    $groupedAttributes[$attrName] = [];
                }
                if (!in_array($value, $groupedAttributes[$attrName])) {
                    $groupedAttributes[$attrName][] = $value;
                }
            }
        }

        $imagePath = null;
        if ($product->image) {
            $fullPath = public_path($product->image);
            if (file_exists($fullPath)) {
                $imagePath = $this->prepareImage($fullPath, self::PRODUCT_IMAGE_MAX, $tempFiles);
            }
        }

        $prices = $product->variants->pluck('price')->filter()->values();
        $minPrice = $prices->min();
        $maxPrice = $prices->max();
        $priceDisplay = null;
        if ($minPrice !== null) {
            $priceDisplay = $minPrice == $maxPrice
                ? '$' . number_format($minPrice, 2)
                : '$' . number_format($minPrice, 2) . ' - $' . number_format($maxPrice, 2);
        }

        return [
            'name' => $product->name,
            'sku' => $product->sku,
            'image_path' => $imagePath,
            'attributes' => $groupedAttributes,
            'price' => $priceDisplay,
        ];
    }

    private function renderPage(\Mpdf\Mpdf $mpdf, array $productArray, int $cols, int $rows, string $layout, int $pageIndex, bool $isFirstProductPage, int $startIndex = 1, int $totalProducts = 0): void
    {
        if (!$isFirstProductPage || $pageIndex > 0) {
            $mpdf->AddPage();
        }
        $pageHtml = view('admin.catalog.pdf-page', compact('productArray', 'cols', 'rows', 'layout', 'startIndex', 'totalProducts'))->render();
        $mpdf->WriteHTML($pageHtml, \Mpdf\HTMLParserMode::HTML_BODY);
    }

    private function cleanupTemp(array $files): void
    {
        foreach ($files as $f) {
            if (is_string($f) && file_exists($f)) {
                @unlink($f);
            }
        }
    }

    /**
     * Resize the source image so its longest side is at most $maxSide px,
     * convert it to a JPEG temp file, and return the temp path. This is the
     * single biggest win for memory: a 4000×3000 PNG costs ~48 MB of RAM
     * inside mPDF; the same picture at 600 px costs ~1 MB.
     *
     * Falls back to the original path if GD isn't available.
     */
    private function prepareImage(string $path, int $maxSide, array &$tempFiles): ?string
    {
        if (!file_exists($path)) {
            return null;
        }
        if (!extension_loaded('gd')) {
            return $path;
        }

        $info = @getimagesize($path);
        $mime = $info[2] ?? null;

        $src = null;
        switch ($mime) {
            case IMAGETYPE_JPEG:
                $src = @imagecreatefromjpeg($path);
                break;
            case IMAGETYPE_PNG:
                $src = @imagecreatefrompng($path);
                break;
            case IMAGETYPE_GIF:
                $src = @imagecreatefromgif($path);
                break;
            case IMAGETYPE_WEBP:
                if (function_exists('imagecreatefromwebp')) {
                    $src = @imagecreatefromwebp($path);
                }
                break;
            default:
                // Try mime_content_type as a fallback
                $mt = @mime_content_type($path);
                if ($mt === 'image/webp' && function_exists('imagecreatefromwebp')) {
                    $src = @imagecreatefromwebp($path);
                } elseif ($mt === 'image/jpeg') {
                    $src = @imagecreatefromjpeg($path);
                } elseif ($mt === 'image/png') {
                    $src = @imagecreatefrompng($path);
                }
        }

        if (!$src) {
            // Couldn't decode; if it's already JPEG/PNG return original
            if (in_array($mime, [IMAGETYPE_JPEG, IMAGETYPE_PNG])) {
                return $path;
            }
            return null;
        }

        $w = imagesx($src);
        $h = imagesy($src);

        if ($w <= $maxSide && $h <= $maxSide && $mime === IMAGETYPE_JPEG) {
            // Already small + JPEG: skip resize, keep original on disk
            imagedestroy($src);
            return $path;
        }

        $ratio = min($maxSide / $w, $maxSide / $h, 1.0);
        $newW = max(1, (int) round($w * $ratio));
        $newH = max(1, (int) round($h * $ratio));

        $dst = imagecreatetruecolor($newW, $newH);
        // Flatten alpha onto white so JPEG looks right
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefilledrectangle($dst, 0, 0, $newW, $newH, $white);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $w, $h);

        $tmpPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'catalog_' . uniqid('', true) . '.jpg';
        imagejpeg($dst, $tmpPath, self::JPEG_QUALITY);

        imagedestroy($src);
        imagedestroy($dst);

        $tempFiles[] = $tmpPath;
        return $tmpPath;
    }
}
