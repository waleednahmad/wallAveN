<?php

namespace App\Livewire\Dashboard\Categories;

use App\Models\Category;
use App\Traits\GenerateSlugsTrait;
use App\Traits\UploadImageTrait;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCategoryForm extends Component
{
    use UploadImageTrait, GenerateSlugsTrait, WithFileUploads;

    #[Validate(['required', 'string', 'max:255'])]
    public $name;

    #[Validate(['boolean'])]
    public $status = 1;

    #[Validate(['nullable', 'image'])]
    public $image;

    public function save()
    {

        $this->validate();

        $category = Category::create([
            'name' => $this->name,
            'slug' => $this->generateUniqueSlug(new Category(), $this->name, 'slug'),
            'status' => $this->status,
            'image' => $this->image ? $this->saveImage($this->image, 'categories') : null,
        ]);

        $this->reset(['name', 'status', 'image']);
        $this->dispatch('success', 'Category created successfully.');
        $this->dispatch('refreshCategories');
        $this->dispatch('closeCreateForm');
    }

    public function render()
    {
        return view('livewire.dashboard.categories.create-category-form');
    }
}
