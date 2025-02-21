<?php

namespace App\Livewire\Dashboard\SubCategories;

use App\Models\Category;
use App\Models\SubCategory;
use App\Traits\GenerateSlugsTrait;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EditSubCategoryForm extends Component
{
    use GenerateSlugsTrait;

    public $subCategory;
    #[Validate(['required', 'string', 'max:255'])]
    public $name;

    #[Validate(['boolean'])]
    public $status = 1;

    #[Validate(['required', 'exists:categories,id'])]
    public $category_id;

    #[On('editSubCategory')]
    public function editSubCategory(SubCategory $subCategory)
    {
        $this->reset();
        $this->subCategory = $subCategory;
        $this->name = $subCategory['name'];
        $this->status = $subCategory['status'];
        $this->category_id = $subCategory['category_id'];
    }

    // =============================
    // ========= COMPUTED =========
    // =============================
    #[Computed()]
    public function categories()
    {
        return Category::orderBy('name')->get();
    }

    public function save()
    {
        $this->validate();

        $this->subCategory->update([
            'name' => $this->name,
            'slug' => $this->generateUniqueSlug($this->subCategory, $this->name),
            'status' => $this->status,
            'category_id' => $this->category_id,
        ]);

        $this->dispatch('refreshSubCategories');
        $this->dispatch('closeEditOffcanvas');
        $this->dispatch('success', 'Sub Category updated successfully');
    }
    public function render()
    {
        return view('livewire.dashboard.sub-categories.edit-sub-category-form');
    }
}
