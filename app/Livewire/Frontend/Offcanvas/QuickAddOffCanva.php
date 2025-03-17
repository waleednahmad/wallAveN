<?php

namespace App\Livewire\Frontend\Offcanvas;

use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Component;

class QuickAddOffCanva extends Component
{
    public $product;
    public $name, $description, $image;
    public $defaultVariant;
    public $groupedAttributes = [];
    public $price ,$compare_at_price;
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
        $this->dispatch('error', 'No variant found for the selected attributes');
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

    public function render()
    {
        return view('livewire.frontend.offcanvas.quick-add-off-canva')->with([
            'variantNotFound' => $this->variantNotFound,
        ]);
    }
}
