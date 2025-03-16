<?php

namespace App\Livewire\Frontend\Components;

use Livewire\Component;

class ProductCardComponent extends Component
{
    public $product;
    public $hasManyVariants = false;
    public $price = 0;
    public $compare_at_price = 0;

    public function mount($product)
    {
        $this->product = $product;
        $firstVariant = $product->variants()->first();
        $this->price = $firstVariant->price;
        $this->compare_at_price = $firstVariant->compare_at_price;

        if ($this->product->variants->count() > 1) {
            $this->hasManyVariants = true;
            $this->price = $this->product->variants->min('price');
        }
    }

    public function openProductOptions()
    {

        $this->dispatch('openProductOptions', [
            'product' => $this->product->id,
        ]);
    }

    public function render()
    {
        return view('livewire.frontend.components.product-card-component');
    }
}
