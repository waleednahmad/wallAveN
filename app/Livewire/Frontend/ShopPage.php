<?php

namespace App\Livewire\Frontend;

use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

class ShopPage extends Component
{
    public $perPage = 12;

    // ======= Filteration =======
    #[Url()]
    public $search = '';
    public $type = '';
    public $selectedCategories = [];
    public $selectedSubCategories = [];
    public $selectedProductTypes = [];
    public $productTypes = [];
    public $subCategories = [];

    public function loadMore()
    {
        $this->perPage += 9;
    }

    public function setProperty($key, $value)
    {
        switch ($key) {
            case 'selectedCategories':
                $this->toggleSelection($this->selectedCategories, $value);
                $this->updateSubCategories();
                break;

            case 'selectedSubCategories':
                $this->toggleSelection($this->selectedSubCategories, $value);
                $this->updateProductTypes();
                break;

            case 'selectedProductTypes':
                $this->toggleSelection($this->selectedProductTypes, $value);
                break;
        }
    }

    private function toggleSelection(&$array, $value)
    {
        if (in_array($value, $array)) {
            $array = array_diff($array, [$value]);
        } else {
            $array[] = $value;
        }
    }

    private function updateSubCategories()
    {
        // Clear selected subcategories
        $this->selectedSubCategories = [];
        $this->selectedProductTypes = [];
        if (empty($this->selectedCategories)) {
            $this->subCategories = [];
        } else {
            $this->subCategories = Category::whereIn('id', $this->selectedCategories)
                ->with(['subCategories' => function ($query) {
                    $query->whereHas('products', function ($query) {
                        $query->where('status', 1)->whereHas('variants');
                    })->withCount(['products' => function ($query) {
                        $query->where('status', 1)->whereHas('variants');
                    }])->orderBy('name');
                }])->get()->pluck('subCategories')->flatten();
        }
    }

    private function updateProductTypes()
    {
        // Clear selected product types
        $this->selectedProductTypes = [];
        if (empty($this->selectedSubCategories)) {
            $this->productTypes = [];
        } else {
            $this->productTypes = SubCategory::whereIn('id', $this->selectedSubCategories)
                ->with(['productTypes' => function ($query) {
                    $query->whereHas('products', function ($query) {
                        $query->where('status', 1)->whereHas('variants');
                    })->withCount(['products' => function ($query) {
                        $query->where('status', 1)->whereHas('variants');
                    }])->orderBy('name');
                }])->get()->pluck('productTypes')->flatten();
        }
    }

    public function clearAllFilters()
    {
        $this->search = '';
        $this->selectedCategories = [];
        $this->selectedSubCategories = [];
        $this->selectedProductTypes = [];
        $this->productTypes = [];
        $this->subCategories = [];
    }

    #[Computed()]
    public function categories()
    {
        return Category::active()->whereHas('products', function ($query) {
            $query->where('status', 1)->whereHas('variants');
        })->withCount(['products' => function ($query) {
            $query->where('status', 1)->whereHas('variants');
        }])->orderBy('name')->get();
    }

    public function render()
    {
        $productsQuery = Product::whereHas('variants')->active()->with(['vendor']);

        if ($this->search) {
            $productsQuery->where('name', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->selectedCategories)) {
            $productsQuery->whereHas('categories', function ($query) {
                $query->whereIn('categories.id', $this->selectedCategories);
            });
        } else {
            $this->selectedSubCategories = [];
            $this->subCategories = [];
        }

        if (!empty($this->selectedSubCategories)) {
            $productsQuery->whereHas('subCategories', function ($query) {
                $query->whereIn('sub_categories.id', $this->selectedSubCategories);
            });
        } else {
            $this->productTypes = [];
        }

        if (!empty($this->selectedProductTypes)) {
            $productsQuery->whereHas('productTypes', function ($query) {
                $query->whereIn('product_types.id', $this->selectedProductTypes);
            });
        }


        return view('livewire.frontend.shop-page')->with([
            'products' => $productsQuery->distinct()->paginate($this->perPage),
        ]);
    }
}
