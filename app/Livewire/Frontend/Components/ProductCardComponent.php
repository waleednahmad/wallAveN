<?php

namespace App\Livewire\Frontend\Components;

use Livewire\Component;

class ProductCardComponent extends Component
{
    public $product;

    public function mount($product)
    {
        $this->product = $product;
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
