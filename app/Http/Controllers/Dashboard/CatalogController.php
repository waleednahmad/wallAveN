<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index()
    {
        return view('admin.catalog.index');
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'layout' => 'required|in:2x3,3x3',
            'footer_text' => 'nullable|string|max:500',
            'front_cover' => 'nullable|image|max:4096',
            'back_cover' => 'nullable|image|max:4096',
            'category_id' => 'nullable|integer|exists:categories,id',
            'subcategory_ids' => 'nullable|array',
            'subcategory_ids.*' => 'integer|exists:sub_categories,id',
        ]);

        $layout = $validated['layout'];
        $footerText = $validated['footer_text'] ?? '';
        $tempFiles = [];

        // Handle cover uploads – save to temp files so mPDF reads from disk
        $frontCoverPath = null;
        if ($request->hasFile('front_cover')) {
            $frontCoverPath = $this->convertToLocalPath($request->file('front_cover')->getRealPath(), $tempFiles);
        }

        $backCoverPath = null;
        if ($request->hasFile('back_cover')) {
            $backCoverPath = $this->convertToLocalPath($request->file('back_cover')->getRealPath(), $tempFiles);
        }

        // Get logo as local file path
        $logoSetting = \App\Models\PublicSetting::where('key', 'main logo')->first()?->value ?? 'assets/img/logo.webp';
        $logoPath = $this->resolveImagePath(public_path($logoSetting), $tempFiles);

        // Get all active products with variants and attribute values
        $query = Product::active()
            ->with(['variants.attributeValues.attribute'])
            ->orderBy('name');

        // Filter by category if selected
        if (!empty($validated['category_id'])) {
            $query->whereHas('categories', function ($q) use ($validated) {
                $q->where('categories.id', $validated['category_id']);
            });
        }

        // Filter by subcategories if selected
        if (!empty($validated['subcategory_ids'])) {
            $query->whereHas('subCategories', function ($q) use ($validated) {
                $q->whereIn('sub_categories.id', $validated['subcategory_ids']);
            });
        }

        $products = $query->get();

        // Build product data with grouped attributes
        $productData = $products->map(function ($product) use (&$tempFiles) {
            $groupedAttributes = [];
            foreach ($product->variants as $variant) {
                foreach ($variant->attributeValues as $attributeValue) {
                    $attributeName = $attributeValue->attribute->name;
                    $attributeValueValue = $attributeValue->value;
                    if (!isset($groupedAttributes[$attributeName])) {
                        $groupedAttributes[$attributeName] = [];
                    }
                    if (!in_array($attributeValueValue, $groupedAttributes[$attributeName])) {
                        $groupedAttributes[$attributeName][] = $attributeValueValue;
                    }
                }
            }

            // Resolve product image to a local file path mPDF can read
            $imagePath = null;
            if ($product->image) {
                $fullPath = public_path($product->image);
                if (file_exists($fullPath)) {
                    $imagePath = $this->resolveImagePath($fullPath, $tempFiles);
                }
            }

            // Get price from variants
            $prices = $product->variants->pluck('price')->filter()->values();
            $minPrice = $prices->min();
            $maxPrice = $prices->max();
            $priceDisplay = null;
            if ($minPrice !== null) {
                if ($minPrice == $maxPrice) {
                    $priceDisplay = '$' . number_format($minPrice, 2);
                } else {
                    $priceDisplay = '$' . number_format($minPrice, 2) . ' - $' . number_format($maxPrice, 2);
                }
            }

            return [
                'name' => $product->name,
                'sku' => $product->sku,
                'image_path' => $imagePath,
                'attributes' => $groupedAttributes,
                'price' => $priceDisplay,
            ];
        });

        // Calculate grid
        if ($layout === '2x3') {
            $cols = 2;
            $rows = 3;
        } else {
            $cols = 3;
            $rows = 3;
        }
        $perPage = $cols * $rows;

        // Chunk products into pages
        $pages = $productData->chunk($perPage);

        // Build PDF using mPDF instance directly to avoid chunk/header issues
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
        ]);

        $css = view('admin.catalog.pdf-styles', compact('layout'))->render();
        $mpdf->WriteHTML($css);

        // -- Front cover (no header/footer, full margins reset) --
        if ($frontCoverPath) {
            $mpdf->SetHTMLHeader('');
            $mpdf->SetHTMLFooter('');
            $coverHtml = view('admin.catalog.pdf-cover', ['imagePath' => $frontCoverPath])->render();
            $mpdf->WriteHTML($coverHtml, \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->AddPage();
        }

        // -- Define header/footer HTML for product pages --
        $headerHtml = view('admin.catalog.pdf-header', compact('logoPath'))->render();
        $footerHtml = view('admin.catalog.pdf-footer', compact('footerText'))->render();

        $mpdf->SetHTMLHeader($headerHtml);
        $mpdf->SetHTMLFooter($footerHtml);

        // -- Product pages (render each page separately to keep HTML small) --
        foreach ($pages as $pageIndex => $pageProducts) {
            if ($pageIndex > 0) {
                $mpdf->AddPage();
            }
            $productArray = $pageProducts->values()->all();
            $pageHtml = view('admin.catalog.pdf-page', compact('productArray', 'cols', 'rows', 'layout'))->render();
            $mpdf->WriteHTML($pageHtml, \Mpdf\HTMLParserMode::HTML_BODY);
        }

        // -- Back cover (no header/footer) --
        if ($backCoverPath) {
            $mpdf->AddPage();
            $mpdf->SetHTMLHeader('');
            $mpdf->SetHTMLFooter('');
            $coverHtml = view('admin.catalog.pdf-cover', ['imagePath' => $backCoverPath])->render();
            $mpdf->WriteHTML($coverHtml, \Mpdf\HTMLParserMode::HTML_BODY);
        }

        $pdfContent = $mpdf->Output('', 'S');

        // Clean up any temp files created for webp conversion
        foreach ($tempFiles as $tmp) {
            @unlink($tmp);
        }

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="product-catalog.pdf"',
        ]);
    }

    /**
     * If the image is webp, convert to png temp file (mPDF doesn't support webp).
     * Otherwise return the original path.
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

    /**
     * Convert an uploaded file path, handling webp conversion if needed.
     */
    private function convertToLocalPath(string $realPath, array &$tempFiles): ?string
    {
        return $this->resolveImagePath($realPath, $tempFiles);
    }
}
