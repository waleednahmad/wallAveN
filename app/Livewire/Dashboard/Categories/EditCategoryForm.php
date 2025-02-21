<?php

namespace App\Livewire\Dashboard\Categories;

use App\Models\Category;
use App\Traits\GenerateSlugsTrait;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EditCategoryForm extends Component
{
    use GenerateSlugsTrait;

    public $category;
    #[Validate(['required', 'string', 'max:255'])]
    public $name;

    #[Validate(['boolean'])]
    public $status = 1;

    #[On('editCategory')]
    public function edit(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->status = $category->status;
    }

    public function save()
    {
        $this->validate();

        $this->category->update([
            'name' => $this->name,
            'slug' => $this->generateUniqueSlug($this->category, $this->name),
            'status' => $this->status,
        ]);

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
