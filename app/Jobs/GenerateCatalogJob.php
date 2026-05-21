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

    /**
     * Allow long-running PDF generation.
     * 30 minutes should be enough for ~400 products.
     */
    public int $timeout = 1800;

    /**
     * Don't retry – a failure should be visible to the user immediately.
     */
    public int $tries = 1;

    public function __construct(public int $catalogId)
    {
    }

    public function handle(): void
    {
        $catalog = Catalog::find($this->catalogId);

        if (!$catalog) {
            return;
        }

        $catalog->update([
            'status' => Catalog::STATUS_PROCESSING,
            'started_at' => now(),
            'error_message' => null,
        ]);

        $tempFiles = [];

        try {
            // Resolve cover paths from storage to absolute filesystem paths
            $frontCoverAbs = $catalog->front_cover_path
                ? $this->resolveImagePath(Storage::disk('local')->path($catalog->front_cover_path), $tempFiles)
                : null;

            $backCoverAbs = $catalog->back_cover_path
                ? $this->resolveImagePath(Storage::disk('local')->path($catalog->back_cover_path), $tempFiles)
                : null;

            // Logo
            $logoSetting = PublicSetting::where('key', 'main logo')->first()?->value ?? 'assets/img/logo.webp';
            $logoPath = $this->resolveImagePath(public_path($logoSetting), $tempFiles);

            // Product query with optional filters
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

            $products = $query->get();

            $productData = $products->map(function ($product) use (&$tempFiles) {
                $groupedAttributes = [];
                foreach ($product->variants as $variant) {
                    foreach ($variant->attributeValues as $attributeValue) {
                        $attributeName = $attributeValue->attribute->name;
                        $value = $attributeValue->value;
                        if (!isset($groupedAttributes[$attributeName])) {
                            $groupedAttributes[$attributeName] = [];
                        }
                        if (!in_array($value, $groupedAttributes[$attributeName])) {
                            $groupedAttributes[$attributeName][] = $value;
                        }
                    }
                }

                $imagePath = null;
                if ($product->image) {
                    $fullPath = public_path($product->image);
                    if (file_exists($fullPath)) {
                        $imagePath = $this->resolveImagePath($fullPath, $tempFiles);
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
            });

            $layout = $catalog->layout;
            if ($layout === '2x3') {
                $cols = 2;
                $rows = 3;
            } else {
                $cols = 3;
                $rows = 3;
            }
            $perPage = $cols * $rows;
            $pages = $productData->chunk($perPage);

            // Build PDF
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'default_font_size' => 10,
                'default_font' => 'sans-serif',
                'margin_left' => 5,
                'margin_right' => 5,
                'margin_top' => 20,
                'margin_bottom' => 14,
                'margin_header' => 3,
                'margin_footer' => 3,
                'tempDir' => storage_path('app/mpdf-temp'),
            ]);

            // Ensure mpdf temp dir exists
            if (!is_dir(storage_path('app/mpdf-temp'))) {
                @mkdir(storage_path('app/mpdf-temp'), 0775, true);
            }

            $css = view('admin.catalog.pdf-styles', ['layout' => $layout])->render();
            $mpdf->WriteHTML($css);

            // Front cover
            if ($frontCoverAbs) {
                $mpdf->SetHTMLHeader('');
                $mpdf->SetHTMLFooter('');
                $coverHtml = view('admin.catalog.pdf-cover', ['imagePath' => $frontCoverAbs])->render();
                $mpdf->WriteHTML($coverHtml, \Mpdf\HTMLParserMode::HTML_BODY);
                $mpdf->AddPage();
            }

            // Header / footer for product pages
            $headerHtml = view('admin.catalog.pdf-header', ['logoPath' => $logoPath])->render();
            $footerHtml = view('admin.catalog.pdf-footer', ['footerText' => $catalog->footer_text ?? ''])->render();
            $mpdf->SetHTMLHeader($headerHtml);
            $mpdf->SetHTMLFooter($footerHtml);

            // Pages
            foreach ($pages as $pageIndex => $pageProducts) {
                if ($pageIndex > 0) {
                    $mpdf->AddPage();
                }
                $productArray = $pageProducts->values()->all();
                $pageHtml = view('admin.catalog.pdf-page', compact('productArray', 'cols', 'rows', 'layout'))->render();
                $mpdf->WriteHTML($pageHtml, \Mpdf\HTMLParserMode::HTML_BODY);
            }

            // Back cover
            if ($backCoverAbs) {
                $mpdf->AddPage();
                $mpdf->SetHTMLHeader('');
                $mpdf->SetHTMLFooter('');
                $coverHtml = view('admin.catalog.pdf-cover', ['imagePath' => $backCoverAbs])->render();
                $mpdf->WriteHTML($coverHtml, \Mpdf\HTMLParserMode::HTML_BODY);
            }

            // Save PDF to storage
            $filename = 'catalogs/catalog-' . $catalog->id . '-' . now()->format('Ymd-His') . '.pdf';
            $absoluteOutput = Storage::disk('local')->path($filename);
            if (!is_dir(dirname($absoluteOutput))) {
                @mkdir(dirname($absoluteOutput), 0775, true);
            }
            $mpdf->Output($absoluteOutput, \Mpdf\Output\Destination::FILE);

            $catalog->update([
                'status' => Catalog::STATUS_COMPLETED,
                'completed_at' => now(),
                'file_path' => $filename,
                'products_count' => $products->count(),
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
            foreach ($tempFiles as $tmp) {
                @unlink($tmp);
            }
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

    /**
     * Convert webp to png temp file (mPDF doesn't support webp).
     */
    private function resolveImagePath(string $path, array &$tempFiles): ?string
    {
        if (!file_exists($path)) {
            return null;
        }

        $mime = @mime_content_type($path);

        if ($mime && str_contains($mime, 'webp') && function_exists('imagecreatefromwebp')) {
            $webp = @imagecreatefromwebp($path);
            if (!$webp) {
                return null;
            }
            $tmpPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'catalog_' . uniqid() . '.png';
            imagepng($webp, $tmpPath);
            imagedestroy($webp);
            $tempFiles[] = $tmpPath;
            return $tmpPath;
        }

        return $path;
    }
}
