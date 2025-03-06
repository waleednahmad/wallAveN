<?php

namespace App\Livewire\Dashboard\Products;

use Livewire\Attributes\On;
use Livewire\Component;

class ProductVariantsPage extends Component
{
    public $product;

    public function mount($product)
    {
        $this->product = $product;
    }
    public function addVariant()
    {
        $this->dispatch('openCreateVariantOffcanvas');
    }

    public function editVariant($variant)
    {
        $this->dispatch('openEditVariantOffcanvas', ['variant' => $variant]);
    }

    public function editVariantAttributes($variant)
    {
        $this->dispatch('openEditVariantAttributesOffcanvas', ['variant' => $variant]);
    }

    #[On('refreshProductVariantsTable')]
    public function render()
    {
        return view('livewire.dashboard.products.product-variants-page');
    }
}
