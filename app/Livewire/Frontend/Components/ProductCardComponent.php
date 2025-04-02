<?php

namespace App\Livewire\Frontend\Components;

use Livewire\Component;

class ProductCardComponent extends Component
{
    public $product;
    public $hasManyVariants = false;
    public $price = 0;
    public $compare_at_price = 0;
    public $variantImages = [];

    public function mount($product)
    {
        $this->product = $product;
        $firstVariant = $product->firstVariant;
        $this->price = $firstVariant->price;
        $this->compare_at_price = $firstVariant->compare_at_price;

        if ($this->product->variants->count() > 1) {
            $this->hasManyVariants = true;
            $this->price = $this->product->variants->min('price');

            $colorAttribute = $this->product->attributes()->where('name', 'like', '%color%')->first();

            if ($colorAttribute) {
                // Select distinct variants based on the "color" attribute
                $distinctVariants = $this->product->variants->filter(function ($variant) use ($colorAttribute) {
                    return $variant->attributeValues->contains('attribute_id', $colorAttribute->id);
                })->unique(function ($variant) use ($colorAttribute) {
                    return $variant->attributeValues->firstWhere('attribute_id', $colorAttribute->id)->id ?? null;
                });

                // Set variant images thumbnails (limit to 4)
                $this->variantImages = $distinctVariants->take(4)->map(function ($variant) {
                    return $variant->image;
                })->toArray();
            } else {
                // Fallback if no "color" attribute is found
                $this->variantImages = $this->product->variants->map(function ($variant) {
                    return $variant->image;
                })->take(4)->toArray();
            }
        }
    }

    public function openProductOptions()
    {

        $this->dispatch('openQuickAdd', [
            'product' => $this->product->id,
        ]);
    }

    public function render()
    {
        return view('livewire.frontend.components.product-card-component');
    }
}
