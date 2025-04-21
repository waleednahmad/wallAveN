<?php

namespace App\Livewire\Dashboard\Categories;

use App\Models\Category;
use App\Traits\GenerateSlugsTrait;
use App\Traits\UploadImageTrait;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditCategoryForm extends Component
{
    use GenerateSlugsTrait, WithFileUploads, UploadImageTrait;

    public $category;
    #[Validate(['required', 'string', 'max:255'])]
    public $name;

    #[Validate(['boolean'])]
    public $status = 1;

    #[Validate(['nullable', 'image'])]
    public $image;

    #[Validate(['nullable', 'image'])]
    public $breadcrumb_image;

    #[Validate(['nullable', 'string'])]
    public $description;

    #[On('editCategory')]
    public function edit(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->status = $category->status;
        $this->breadcrumb_image = null;
        $this->description = $category->description;
    }

    public function save()
    {
        $this->validate();
        $old_iamge = $this->category->image;
        $old_breadcrumb_image = $this->category->breadcrumb_image;

        $this->category->update([
            'name' => $this->name,
            'slug' => $this->generateUniqueSlug($this->category, $this->name, 'slug'),
            'status' => $this->status,
            'description' => $this->description,
        ]);

        if ($this->image) {
            $this->category->update([
                'image' => $this->saveImage($this->image, 'categories'),
            ]);

            if ($old_iamge && file_exists(public_path($old_iamge))) {
                unlink(public_path($old_iamge));
            }
        }



        if ($this->breadcrumb_image) {
            $this->category->update([
                'breadcrumb_image' => $this->saveImage($this->breadcrumb_image, 'categories/breadcrumbs'),
            ]);

            if ($old_breadcrumb_image && file_exists(public_path($old_breadcrumb_image))) {
                unlink(public_path($old_breadcrumb_image));
            }
        }

        $this->reset(['name', 'status']);
        $this->dispatch('success', 'Category updated successfully.');
        $this->dispatch('refreshCategories');
        $this->dispatch('closeEditForm');
    }

    public function render()
    {
        return view('livewire.dashboard.categories.edit-category-form');
    }
}
