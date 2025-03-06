<?php

namespace App\Livewire\Dashboard\SubCategories;

use App\Models\Category;
use App\Models\SubCategory;
use App\Traits\GenerateSlugsTrait;
use App\Traits\UploadImageTrait;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateSubCategoryForm extends Component
{
    use UploadImageTrait, GenerateSlugsTrait, WithFileUploads;


    #[Validate(['required', 'string', 'max:255'])]
    public $name;

    #[Validate(['boolean'])]
    public $status = 1;

    #[Validate(['required', 'exists:categories,id'])]
    public $category_id;

    #[Validate(['nullable', 'image'])]
    public $image;

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

        $subCategory = SubCategory::create([
            'name' => $this->name,
            'slug' => $this->generateUniqueSlug(new SubCategory(), $this->name),
            'status' => $this->status,
            'category_id' => $this->category_id,
            'image' => $this->image ? $this->saveImage($this->image, 'sub-categories') : null,

        ]);

        $this->reset(['name', 'status', 'category_id']);
        $this->dispatch('success', 'Sub-category created successfully.');
        $this->dispatch('refreshSubCategories');
        $this->dispatch('closeCreateForm');
    }


    public function render()
    {
        return view('livewire.dashboard.sub-categories.create-sub-category-form');
    }
}
