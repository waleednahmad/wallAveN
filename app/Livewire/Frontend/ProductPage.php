<?php

namespace App\Livewire\Frontend;

use App\Models\CartTemp;
use App\Models\Product;
use Illuminate\Container\Attributes\Log;
use Illuminate\Support\Facades\Log as FacadesLog;
use Livewire\Component;

class ProductPage extends Component
{

    public $product;
    public $option1Name;
    public $option1Values = [];
    public $option2Name;
    public $option2Values = [];
    public $option3Name;
    public $option3Values = [];
    public $selectedSku;
    public $image;
    public $title;
    public $vendor;
    public $bodyHtml;
    public $price;

    public $selectedOption1Value;
    public $selectedOption2Value;
    public $selectedOption3Value;
    public $sku;
    public $imagesGallery = [];
    public $relatedProducts = [];

    public $quantity = 1;

    public function mount($product)
    {
        $this->product = $product;
        $this->initializeProductDetails($product);
        $this->initializeOptions($product);
        $this->setDefaultOptionValues();
        $this->updateProductVariant();
        $this->setRelatedProducts();
    }

    private function initializeProductDetails($product)
    {
        $this->sku = $product->sku;
        $this->selectedSku = $product->variant_sku;
        $this->image = $product->variant_image ?? $product->image_src;
        $this->title = $product->title;
        $this->vendor = $product->vendor;
        $this->bodyHtml = $product->body_html;
        $this->price = $product->variant_price ?? 0;
        $this->imagesGallery = Product::where('sku', $product->sku)
            ->whereNotNull('Image Src')
            ->select('Image Src')
            ->get()
            ->pluck('Image Src')
            ->unique();
    }

    private function initializeOptions($product)
    {
        $this->option1Name = $product->option1_name;
        $this->option2Name = $product->option2_name;
        $this->option3Name = $product->option3_name;

        $options = Product::where('sku', $product->sku)
            ->select('Option1 Value', 'Option2 Value', 'Option3 Value')
            ->get();

        $this->option1Values = $options->pluck('Option1 Value')->filter()->unique()->values()->toArray();
        $this->option2Values = $options->pluck('Option2 Value')->filter()->unique()->values()->toArray();
        $this->option3Values = $options->pluck('Option3 Value')->filter()->unique()->values()->toArray();
    }

    private function setRelatedProducts()
    {
        $this->relatedProducts = Product::where('vendor', $this->vendor)
            ->where('sku', '!=', $this->sku)
            ->whereNotNull('tags')
            ->whereNotNull('title')
            ->where('tags', 'like', '%' . $this->product->tags . '%')
            ->take(6)
            ->inRandomOrder()
            ->get();
    }

    private function setDefaultOptionValues()
    {
        $this->selectedOption1Value = $this->option1Values[0] ?? null;
        $this->selectedOption2Value = $this->option2Values[0] ?? null;
        $this->selectedOption3Value = $this->option3Values[0] ?? null;
    }

    public function setSelectedOption1Value($value)
    {
        $this->selectedOption1Value = $value;
        $this->updateProductVariant();
    }

    public function setSelectedOption2Value($value)
    {
        $this->selectedOption2Value = $value;
        $this->updateProductVariant();
    }

    public function setSelectedOption3Value($value)
    {
        $this->selectedOption3Value = $value;
        $this->updateProductVariant();
    }

    private function updateProductVariant()
    {
        $productSize = str_replace(['"', ' ', 'x'], '', $this->selectedOption2Value);
        $productMaterial = strtoupper(substr($this->selectedOption3Value, 0, 2));

        $variantSku = "{$productMaterial}-{$this->sku}-{$productSize}";
        // dd($variantSku);
        $product = Product::where('Variant SKU', $variantSku)->first();

        if ($product) {
            $this->product = $product;
            $this->image = $product->variant_image ?? $this->product->image_src;
            $this->selectedSku = $product->variant_sku;
            $this->price = $product->variant_price;
        } else {
            $this->resetToDefaultVariant();
            $this->dispatch('error', 'This variant is not available');
        }

        $this->quantity = 1;
    }

    private function resetToDefaultVariant()
    {
        $this->selectedOption2Value = $this->option2Values[0] ?? null;
        $this->selectedOption3Value = $this->option3Values[0] ?? null;
        $this->product = Product::where('sku', $this->sku)->whereNotNull('title')->first();
        $this->selectedSku = $this->product->variant_sku;
        $this->image = $this->product->variant_image ?? $this->product->image;
        $this->price = $this->product->variant_price;

        $this->quantity = 1;
    }

    public function increaseQuantity()
    {
        $this->quantity++;
    }

    public function decreaseQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        } else {
            $this->quantity = 1;
            $this->dispatch('error', 'Quantity cannot be less than 1');
        }
    }

    public function addToCart()
    {
        if (!auth('dealer')->check() && !auth('representative')->check()) {
            return redirect()->route('login')->with('error', 'Please login to add item to cart');
        }

        if (auth('representative')->check() && !auth('representative')->user()->buyingFor()->exists()) {
            $this->dispatch('error', 'You need to select a dealer to add item to cart');
            $this->dispatch('openDealerSelectionModal');
            return;
        }

        $item = CartTemp::where('product_id', $this->product->id)
            ->where('variant_sku', $this->selectedSku)
            ->where('option1_value', $this->selectedOption1Value)
            ->where('option2_value', $this->selectedOption2Value)
            ->where('option3_value', $this->selectedOption3Value)
            ->first();

        if ($item) {
            $item->quantity += $this->quantity;
            $item->total = $item->quantity * $item->price;
            $item->save();
        } else {
            CartTemp::create([
                'dealer_id' => auth('dealer')->id() ?? null,
                'representative_id' => auth('representative')->id() ?? null,
                'product_id' => $this->product->id,
                'variant_sku' => $this->selectedSku,
                'variant_image' => $this->image,
                'title' => $this->title,
                'vendor' => $this->vendor,
                'option1_name' => $this->option1Name,
                'option1_value' => $this->selectedOption1Value,
                'option2_name' => $this->option2Name,
                'option2_value' => $this->selectedOption2Value,
                'option3_name' => $this->option3Name,
                'option3_value' => $this->selectedOption3Value,
                'sku' => $this->sku,
                'quantity' => $this->quantity,
                'price' => $this->product->variant_price,
                'total' => $this->quantity * $this->product->variant_price,
            ]);
        }

        $this->dispatch('success', 'Item added to cart');
        $this->dispatch('openCartOffcanva');
        $this->resetToDefaultVariant();
    }



    public function render()
    {
        return view('livewire.frontend.product-page');
    }
}
