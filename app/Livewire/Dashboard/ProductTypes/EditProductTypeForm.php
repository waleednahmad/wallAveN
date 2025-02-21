<?php

namespace App\Livewire\Dashboard\ProductTypes;

use App\Models\ProductType;
use App\Models\SubCategory;
use App\Traits\GenerateSlugsTrait;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EditProductTypeForm extends Component
{
    use GenerateSlugsTrait;

    public $productType;
    #[Validate(['required', 'string', 'max:255'])]
    public $name;

    #[Validate(['boolean'])]
    public $status = 1;

    #[Validate(['required', 'exists:sub_categories,id'])]
    public $sub_category_id;


    #[On('editProductType')]
    public function editProductType(ProductType $productType)
    {
        $this->reset(['name', 'status', 'sub_category_id']);
        $this->productType = $productType;
        $this->name = $productType->name;
        $this->status = $productType->status;
        $this->sub_category_id = $productType->sub_category_id;
    }
    // =============================
    // ========= COMPUTED =========
    // =============================
    #[Computed()]
    public function subCategories()
    {
        return SubCategory::orderBy('name')->get();
    }

    public function save()
    {
        $this->validate();

        $this->productType->update([
            'name' => $this->name,
            'slug' => $this->generateUniqueSlug($this->productType, $this->name),
            'status' => $this->status,
            'sub_category_id' => $this->sub_category_id,
        ]);

        $this->reset(['name', 'status', 'sub_category_id']);
        $this->dispatch('success', 'Product type updated successfully.');
        $this->dispatch('refreshProductTypes');
        $this->dispatch('closeEditForm');
    }

    public function render()
    {
        return view('livewire.dashboard.product-types.edit-product-type-form');
    }
}
