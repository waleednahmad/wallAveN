<?php

namespace App\Livewire\Dashboard\Products\Offcanvas\SubCategories;

use App\Models\Category;
use App\Models\SubCategory;
use App\Traits\GenerateSlugsTrait;
use App\Traits\UploadImageTrait;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateSubCategoryOffcanva extends Component
{
    use UploadImageTrait, GenerateSlugsTrait, WithFileUploads;

    public $mainCategories = [];

    #[Validate(['required', 'string', 'max:255'])]
    public $c_sub_name;

    #[Validate(['boolean'])]
    public $c_sub_status = 1;

    #[Validate(['required', 'exists:categories,id'])]
    public $c_main_category_id;

    #[Validate(['nullable', 'image'])]
    public $c_sub_image;


    public function save()
    {
        $this->validate();

        $subCategory = SubCategory::create([
            'name' => $this->c_sub_name,
            'slug' => $this->generateUniqueSlug(new SubCategory(), $this->c_sub_name),
            'status' => $this->c_sub_status,
            'category_id' => $this->c_main_category_id,
            'image' => $this->c_sub_image ? $this->saveImage($this->c_sub_image, 'sub-categories') : null,

        ]);

        $this->reset();
        $this->dispatch('success', 'Sub-category created successfully.');
        $this->dispatch('refresh');
        $this->dispatch('closeCreateSubCategoryForm');
    }


    #[On('refresh')]
    public function render()
    {
        $this->mainCategories = Category::orderBy('name')->get();
        return view('livewire.dashboard.products.offcanvas.sub-categories.create-sub-category-offcanva');
    }
}
