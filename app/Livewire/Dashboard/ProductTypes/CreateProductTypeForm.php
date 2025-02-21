<?php

namespace App\Livewire\Dashboard\ProductTypes;

use App\Models\ProductType;
use App\Models\SubCategory;
use App\Traits\GenerateSlugsTrait;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateProductTypeForm extends Component
{
    use GenerateSlugsTrait;

    #[Validate(['required', 'string', 'max:255'])]
    public $name;

    #[Validate(['boolean'])]
    public $status = 1;

    #[Validate(['required', 'exists:sub_categories,id'])]
    public $sub_category_id;

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

        $productType = ProductType::create([
            'name' => $this->name,
            'slug' => $this->generateUniqueSlug(new ProductType(), $this->name),
            'status' => $this->status,
            'sub_category_id' => $this->sub_category_id,
        ]);

        $this->reset(['name', 'status', 'sub_category_id']);
        $this->dispatch('success', 'Product type created successfully.');
        $this->dispatch('refreshProductTypes');
        $this->dispatch('closeCreateForm');
    }
    public function render()
    {
        return view('livewire.dashboard.product-types.create-product-type-form');
    }
}
