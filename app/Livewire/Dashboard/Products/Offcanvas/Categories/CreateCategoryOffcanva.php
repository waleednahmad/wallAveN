<?php

namespace App\Livewire\Dashboard\Products\Offcanvas\Categories;

use App\Models\Category;
use App\Traits\GenerateSlugsTrait;
use App\Traits\UploadImageTrait;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCategoryOffcanva extends Component
{
    use UploadImageTrait, GenerateSlugsTrait, WithFileUploads;

    #[Validate(['required', 'string', 'max:255'])]
    public $c_name;

    #[Validate(['boolean'])]
    public $c_status = 1;

    #[Validate(['nullable', 'image'])]
    public $c_image;

    #[Validate(['nullable', 'image'])]
    public $c_breadcrumb_image;

    #[Validate(['nullable', 'string'])]
    public $c_description;

    public function save()
    {

        $this->validate();

        $category = Category::create([
            'name' => $this->c_name,
            'slug' => $this->generateUniqueSlug(new Category(), $this->c_name, 'slug'),
            'status' => $this->c_status,
            'image' => $this->c_image ? $this->saveImage($this->c_image, 'categories') : null,
            'breadcrumb_image' => $this->c_breadcrumb_image ? $this->saveImage($this->c_breadcrumb_image, 'categories/breadcrumbs') : null,
            'description' => $this->c_description,
        ]);

        $this->reset();
        $this->dispatch('success', 'Category created successfully.');
        $this->dispatch('refresh');
        $this->dispatch('closeCreateCategoryForm');
    }
    public function render()
    {
        return view('livewire.dashboard.products.offcanvas.categories.create-category-offcanva');
    }
}
