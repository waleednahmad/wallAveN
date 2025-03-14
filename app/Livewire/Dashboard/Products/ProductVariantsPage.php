<?php

namespace App\Livewire\Dashboard\Products;

use App\Models\ProductVariant;
use Livewire\Attributes\On;
use Livewire\Component;

class ProductVariantsPage extends Component
{
    public $product;

    public function mount($product)
    {
        $this->product = $product->load('variants.attributeValues.attribute');
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


    public function deleteVariant(ProductVariant $variant)
    {

        $this->dispatch('confirmDeleteVariant', [
            'variant' => $variant,
        ]);

    }

    #[On('refreshProductVariantsTable')]
    public function render()
    {
        return view('livewire.dashboard.products.product-variants-page');
    }
}
