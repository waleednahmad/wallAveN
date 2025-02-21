<?php

namespace App\Livewire\Dashboard\Categories;

use App\Models\Category;
use App\Traits\GenerateSlugsTrait;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateCategoryForm extends Component
{
    use GenerateSlugsTrait;

    #[Validate(['required', 'string', 'max:255'])]
    public $name;

    #[Validate(['boolean'])]
    public $status = 1;

    public function save()
    {
        $this->validate();

        $category = Category::create([
            'name' => $this->name,
            'slug' => $this->generateUniqueSlug(new Category(), $this->name),
            'status' => $this->status,
        ]);

        $this->reset(['name', 'status']);
        $this->dispatch('success', 'Category created successfully.');
        $this->dispatch('refreshCategories');
        $this->dispatch('closeCreateForm');
    }

    public function render()
    {
        return view('livewire.dashboard.categories.create-category-form');
    }
}
