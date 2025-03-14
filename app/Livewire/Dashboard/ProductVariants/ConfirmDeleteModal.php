<?php

namespace App\Livewire\Dashboard\ProductVariants;

use App\Models\ProductVariant;
use Livewire\Attributes\On;
use Livewire\Component;

class ConfirmDeleteModal extends Component
{
    public $variant;
    #[On('setVariant')]
    public function setOrderId(ProductVariant $variant)
    {
         $this->variant = $variant;
    }
    public function delete()
    {
        $this->variant->delete();
        $this->variant->attributeValues()->detach();

        return redirect()->route('dashboard.products.create-variant', $this->variant->product_id)
            ->with('success', 'Product variant deleted successfully.');
    }
    public function render()
    {
        return view('livewire.dashboard.product-variants.confirm-delete-modal');
    }
}
