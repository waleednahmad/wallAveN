<?php

namespace App\Livewire\Dashboard;

use App\Models\Category;
use App\Models\SubCategory;
use Livewire\Component;

class CatalogGenerator extends Component
{
    public $categoryId = null;
    public $subcategoryIds = [];
    public $categories = [];
    public $subcategories = [];

    public function mount()
    {
        $this->categories = Category::active()->orderBy('name')->get(['id', 'name'])->toArray();
    }

    public function updatedCategoryId($value)
    {
        $this->subcategoryIds = [];

        if ($value) {
            $this->subcategories = SubCategory::where('category_id', $value)
                ->where('status', 1)
                ->orderBy('name')
                ->get(['id', 'name'])
                ->toArray();
        } else {
            $this->subcategories = [];
        }
    }

    public function render()
    {
        return view('livewire.dashboard.catalog-generator');
    }
}
