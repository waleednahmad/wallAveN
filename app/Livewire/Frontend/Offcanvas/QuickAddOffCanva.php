<?php

namespace App\Livewire\Frontend\Offcanvas;

use App\Models\CartTemp;
use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Component;

class QuickAddOffCanva extends Component
{
    public $product;
    public $name, $description, $image;
    public $defaultVariant;
    public $groupedAttributes = [];
    public $price, $compare_at_price;
    public $selectedAttributeValues = [];
    public $selectedSku;
    public $title;
    public $vendor;
    public $imagesGallery = [];
    public $relatedProducts = [];
    public $quantity = 1;
    public $variantNotFound = false;

    #[On('setProductQuickAdd')]
    public function setProductQuickAdd(Product $product)
    {
        $this->product = $product->load("variants.attributeValues.attribute");
        $this->initializeProductDetails();
        $this->setDefaultAttributeValues();
        $this->updateProductVariant();
    }

    private function initializeProductDetails()
    {
        $groupedAttributes = [];

        foreach ($this->product->variants as $variant) {
            foreach ($variant->attributeValues as $attributeValue) {
                $attributeName = $attributeValue->attribute->name;
                $attributeId = $attributeValue->attribute->id;
                $attributeValueValue = $attributeValue->value;
                $attributeValueId = $attributeValue->id;

                if (!isset($groupedAttributes[$attributeName])) {
                    $groupedAttributes[$attributeName] = [
                        'id' => $attributeId,
                        'values' => []
                    ];
                }

                if (!in_array($attributeValueValue, array_column($groupedAttributes[$attributeName]['values'], 'value'))) {
                    $groupedAttributes[$attributeName]['values'][] = [
                        'id' => $attributeValueId,
                        'value' => $attributeValueValue
                    ];
                }
            }
        }

        $this->groupedAttributes = $groupedAttributes;
        $this->vendor = $this->product->vendor ? $this->product->vendor->name : null;
        $this->description = $this->product->description;
        $this->imagesGallery = $this->product->images;
    }

    public function setDefaultAttributeValues()
    {
        $firstVariant = $this->product->variants->first();
        if ($firstVariant) {
            foreach ($firstVariant->attributeValues as $attributeValue) {
                $attributeId = $attributeValue->attribute->id;
                $this->selectedAttributeValues[$attributeId] = $attributeValue->id;
            }

            $this->defaultVariant = $firstVariant;
            $this->selectedSku = $firstVariant->sku;
            $this->price = $firstVariant->price;
            $this->compare_at_price = $firstVariant->compare_at_price;
            $this->variantNotFound = false;
        }
    }

    private function updateProductVariant()
    {
        $variant = $this->product->variants->first(function ($variant) {
            return collect($this->selectedAttributeValues)->every(function ($value, $key) use ($variant) {
                return in_array($value, $variant->attributeValues->pluck('id')->toArray());
            });
        });

        if ($variant) {
            $this->selectedSku = $variant->sku;
            $this->image = $variant->image ?? $this->product->image;
            $this->price = $variant->price;
            $this->compare_at_price = $variant->compare_at_price;
            $this->variantNotFound = false;
        } else {
            $this->selectedSku = "---";
            $this->price = "---";
            $this->variantNotFound = true;
        }
    }

    public function resetDefaultVariant()
    {
        $this->selectedAttributeValues = [];
        $this->setDefaultAttributeValues();
        $this->updateProductVariant();
    }

    private function resetToDefaultVariant()
    {
        $this->resetDefaultVariant();
        // $this->dispatch('error', 'No variant found for the selected attributes');
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

    public function selectAttributeValue($attributeId, $valueId)
    {
        $this->selectedAttributeValues[$attributeId] = $valueId;
        $this->updateProductVariant();
    }


    public function addToCart()
    {
        if (!auth('dealer')->check() && !auth('representative')->check() && !auth('web')->check()) {
            return redirect()->route('login')->with('error', 'Please login to add item to cart');
        }

        // Check for the representative
        if (auth('representative')->check() && !auth('representative')->user()->buyingFor()->exists()) {
            $this->dispatch('error', 'You need to select a dealer to add item to cart');
            $this->dispatch('openDealerSelectionModal');
            return;
        }

        // Check for admin
        if (auth('web')->check() && !auth('web')->user()->buyingFor()->exists()) {
            $this->dispatch('error', 'You need to select a dealer to add item to cart');
            $this->dispatch('openDealerSelectionModal');
            return;
        }

        $variant = $this->product->variants->firstWhere('sku', $this->selectedSku);


        if (!$variant) {
            $this->dispatch('error', 'Selected variant not found');
            return;
        }

        // get the price for the variant
        $compare_at_price = $variant->compare_at_price;
        $price = $variant->price;
        $variant_price = 0;
        if ($variant->compare_at_price && $variant->compare_at_price < $variant->price && $variant->compare_at_price > 0) {
            $variant_price = $compare_at_price;
        } else {
            $variant_price = $price;
        }

        $item = CartTemp::where('variant_id', $variant->id)->first();

        if ($item) {
            $item->quantity += $this->quantity;
            $item->total = $item->quantity * $variant_price;
            $item->save();
        } else {


            CartTemp::create([
                'dealer_id' => auth('dealer')->id() ?? null,
                'representative_id' => auth('representative')->id() ?? null,
                'admin_id' => auth('web')->id() ?? null,
                'product_id' => $this->product->id,
                'variant_id' => $variant->id,
                'item_type' => 'variant',
                'name' => $this->product->name,
                'image' => $variant->image ?? $this->product->image,
                'vendor' => $this->product->vendor ? $this->product->vendor->name : null,
                'sku' => $variant->sku,
                'price' => $variant_price,
                'total' => $this->quantity * $variant_price,
                'quantity' => $this->quantity,
                'attributes' => $variant->attributeValues->pluck('value', 'attribute.name')->toJson(),
            ]);
        }

        $this->reset('quantity');
        $this->dispatch('notify');
        $this->resetToDefaultVariant();
    }


    public function previewCart()
    {
        $this->dispatch('closeQuickAdd');
        $this->dispatch('openCartOffcanva');
    }

    public function render()
    {
        return view('livewire.frontend.offcanvas.quick-add-off-canva')->with([
            'variantNotFound' => $this->variantNotFound,
        ]);
    }
}
