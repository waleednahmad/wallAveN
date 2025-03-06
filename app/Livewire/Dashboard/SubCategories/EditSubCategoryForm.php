<?php

namespace App\Livewire\Dashboard\SubCategories;

use App\Models\Category;
use App\Models\SubCategory;
use App\Traits\GenerateSlugsTrait;
use App\Traits\UploadImageTrait;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditSubCategoryForm extends Component
{
    use GenerateSlugsTrait, WithFileUploads, UploadImageTrait;


    public $subCategory;
    #[Validate(['required', 'string', 'max:255'])]
    public $name;

    #[Validate(['boolean'])]
    public $status = 1;

    #[Validate(['required', 'exists:categories,id'])]
    public $category_id;

    #[Validate(['nullable', 'image'])]
    public $image;

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

        $old_iamge = $this->subCategory->image;
        $this->subCategory->update([
            'name' => $this->name,
            'slug' => $this->generateUniqueSlug($this->subCategory, $this->name),
            'status' => $this->status,
            'category_id' => $this->category_id,
        ]);

        if ($this->image) {
            $this->subCategory->update([
                'image' => $this->saveImage($this->image, 'sub-categories'),
            ]);

            if ($old_iamge && file_exists(public_path($old_iamge))) {
                unlink(public_path($old_iamge));
            }
        }

        $this->dispatch('refreshSubCategories');
        $this->dispatch('closeEditOffcanvas');
        $this->dispatch('success', 'Sub Category updated successfully');
    }
    public function render()
    {
        return view('livewire.dashboard.sub-categories.edit-sub-category-form');
    }
}
