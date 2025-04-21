<?php

namespace App\Livewire\Frontend;

use App\Models\CartTemp;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ProductPage extends Component
{
    public $product;
    public $name, $description, $image;
    public $defaultVariant;
    public $groupedAttributes = [];
    public $price;
    public $compare_at_price;
    public $selectedAttributeValues = [];

    public $selectedSku;
    public $title;
    public $vendor;
    public $bodyHtml;
    public $imagesGallery = [];
    public $relatedProducts = [];
    public $quantity = 1;
    public $variantNotFound = false;
    public $selectedVariantImage = null;


    public function mount($product)
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
        $this->imagesGallery = $this->product->images->sortBy('order');
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
            $this->selectedVariantImage = $firstVariant->image ?? $this->product->image;
            // dd($this->selectedVariantImage);
        }
        // Edit the product price based on the dealer's price list
        if (auth('dealer')->check()) {
            $dealer = auth('dealer')->user();
            $percentage = (float)$dealer->priceList?->percentage ?? 0;
            if ($percentage) {
                $this->price = (float)$this->price - ((float)$this->price * $percentage);
                $this->compare_at_price = (float)$this->compare_at_price - ((float)$this->compare_at_price * $percentage);
            }
        }
        // Edit the product price based on the dealer's price list whe nthe represenatative is logged in and have "buyingFor" dealer
        if (auth('representative')->check() && auth('representative')->user()->buyingFor) {
            $dealer = auth('representative')->user()->buyingFor;
            $percentage = (float)$dealer->priceList?->percentage ?? 0;
            if ($percentage) {
                $this->price = (float)$this->price - ((float)$this->price * $percentage);
                $this->compare_at_price = (float)$this->compare_at_price - ((float)$this->compare_at_price * $percentage);
            }
        }

        // Edit the product price based on the dealer's price list when the admin is logged in and have "buyingFor" dealer
        if (auth('web')->check() && auth('web')->user()->buyingFor) {
            $dealer = auth('web')->user()->buyingFor;
            $percentage = (float)$dealer->priceList?->percentage ?? 0;
            if ($percentage) {
                $this->price = (float)$this->price - ((float)$this->price * $percentage);
                $this->compare_at_price = (float)$this->compare_at_price - ((float)$this->compare_at_price * $percentage);
            }
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
            $this->selectedVariantImage = $variant->image ?? $this->product->image;
        } else {
            $this->selectedSku = "---";
            $this->price = "---";
            $this->compare_at_price = null;
            $this->variantNotFound = true;
        }
        // Edit the product price based on the dealer's price list
        if (auth('dealer')->check()) {
            $dealer = auth('dealer')->user();
            $percentage = (float)$dealer->priceList?->percentage ?? 0;
            if ($percentage) {
                $this->price = (float)$this->price - ((float)$this->price * $percentage);
                $this->compare_at_price = (float)$this->compare_at_price - ((float)$this->compare_at_price * $percentage);
            }
        }

        // Edit the product price based on the dealer's price list whe nthe represenatative is logged in and have "buyingFor" dealer
        if (auth('representative')->check() && auth('representative')->user()->buyingFor) {
            $dealer = auth('representative')->user()->buyingFor;
            $percentage = (float)$dealer->priceList?->percentage ?? 0;
            if ($percentage) {
                $this->price = (float)$this->price - ((float)$this->price * $percentage);
                $this->compare_at_price = (float)$this->compare_at_price - ((float)$this->compare_at_price * $percentage);
            }
        }

        // Edit the product price based on the dealer's price list when the admin is logged in and have "buyingFor" dealer
        if (auth('web')->check() && auth('web')->user()->buyingFor) {
            $dealer = auth('web')->user()->buyingFor;
            $percentage = (float)$dealer->priceList?->percentage ?? 0;
            if ($percentage) {
                $this->price = (float)$this->price - ((float)$this->price * $percentage);
                $this->compare_at_price = (float)$this->compare_at_price - ((float)$this->compare_at_price * $percentage);
            }
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


        $item = CartTemp::where('variant_id', $variant->id)
            ->where(function ($query) {
                if (auth('dealer')->check()) {
                    $query->where('dealer_id', auth('dealer')->user()->id)
                        ->whereNull('representative_id')
                        ->whereNull('admin_id');
                } elseif (auth('representative')->check()) {
                    $query->where('representative_id', auth('representative')->user()->id)
                        ->where('dealer_id', auth('representative')->user()->buyingFor->id)
                        ->whereNull('admin_id');
                } elseif (auth('web')->check()) { // admin check
                    $query->where('admin_id', auth('web')->user()->id)
                        ->where('dealer_id', auth('web')->user()->buyingFor->id)
                        ->whereNull('representative_id');
                }
            })
            ->where('item_type', 'variant')
            ->first();

        if ($item) {
            $item->quantity += $this->quantity;
            $item->total = $item->quantity * $this->price;
            $item->save();
        } else {

            $dealerId = auth('dealer')->check() ? auth('dealer')->id() : null;
            if (auth('representative')->check()) {
                $dealerId = auth('representative')->user()->buyingFor->id;
            }
            if (auth('web')->check()) {
                $dealerId = auth('web')->user()->buyingFor->id;
            }

            CartTemp::create([
                'dealer_id' => $dealerId,
                'representative_id' => auth('representative')->id() ?? null,
                'admin_id' => auth('web')->id() ?? null,
                'product_id' => $this->product->id,
                'variant_id' => $variant->id,
                'item_type' => 'variant',
                'name' => $this->product->name,
                'image' => $variant->image ?? $this->product->image,
                'vendor' => $this->product->vendor ? $this->product->vendor->name : null,
                'sku' => $variant->sku,
                'price' => $this->price,
                'total' => $this->quantity * $this->price,
                'quantity' => $this->quantity,
                'attributes' => $variant->attributeValues->pluck('value', 'attribute.name')->toJson(),
            ]);
        }

        $this->reset('quantity');
        $this->dispatch('openCartOffcanva');
        $this->dispatch('success', 'Item added to cart successfully');
        $this->resetToDefaultVariant();
    }
    public function render()
    {
        $this->dispatch('activateVariantThumbnail', ['image' => $this->selectedVariantImage]);
        return view('livewire.frontend.product-page', [
            'variantNotFound' => $this->variantNotFound,
        ]);
    }
}
