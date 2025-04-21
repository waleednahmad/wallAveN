<?php

namespace App\Livewire\Frontend;

use App\Models\Attribute;
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
    public $searchQuery = '';
    public $type = '';
    public $selectedCategories = [];
    public $selectedSubCategories = [];
    public $selectedProductTypes = [];
    public $selectedAttributeValues = [];
    public $productTypes = [];
    public $subCategories = [];
    
    public $rangePrice;
    public $minValue;
    public $maxValue;
    
    #[Url()]
    public $category ;
    public $selectedCategory;


    public function mount()
    {
        $category = request()->input('category', '');
        if ($category) {
            $this->selectedCategory = Category::where('slug', $category)->first();
            if ($this->selectedCategory) {
                $this->selectedCategories = [$this->selectedCategory->id];
            } else {
                $this->selectedCategories = [];
            }
        }
        $subCategory = request()->input('sub_category', '');
        if ($subCategory) {
            $subCategory = SubCategory::where('slug', $subCategory)->first();
            if ($subCategory) {
                $this->selectedCategories = [$subCategory->category_id];
                $this->selectedSubCategories = [$subCategory->id];
            } else {
                $this->selectedSubCategories = [];
            }
        }





        // set the search from the url if it exists
        $this->search = request()->query('search', '');
        $this->searchQuery = $this->search;
        $this->updateSubCategories();
    }

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
                $this->selectedProductTypes = [];
                $this->selectedAttributeValues = [];
                break;

            case 'selectedSubCategories':
                $this->toggleSelection($this->selectedSubCategories, $value);
                $this->updateProductTypes();
                break;

            case 'selectedProductTypes':
                $this->toggleSelection($this->selectedProductTypes, $value);
                break;

            case 'selectedAttributeValues':
                $this->toggleSelection($this->selectedAttributeValues, $value);
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
        // $this->selectedSubCategories = [];
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
        $this->selectedAttributeValues = [];
        $this->productTypes = [];
        $this->subCategories = [];
    }

    public function applySearch()
    {
        $this->search = $this->searchQuery; // Update the search property with the query
    }

    public function selectCategory($slug)
    {
        $category = Category::where('slug', $slug)->first();
        if ($category) {
            $this->selectedCategory = $category;
            $this->selectedCategories = [$category->id];
            $this->category = $category->slug;
            $this->selectedSubCategories = [];
            $this->selectedProductTypes = [];
            $this->selectedAttributeValues = [];
            $this->updateSubCategories();
            // Update the URL with the selected category slug
            // $this->redirect(request()->url() . '?category=' . $category->slug, navigate: true);
        } else {
            $this->selectedCategory = null;
            $this->selectedCategories = [];
            $this->category = null;
            $this->selectedSubCategories = [];
            $this->selectedProductTypes = [];
            $this->selectedAttributeValues = [];
            $this->subCategories = [];
            $this->productTypes = [];
        }
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
    #[Computed()]
    public function availableAttributes()
    {
        $query = Attribute::query();

        if (!empty($this->selectedCategories)) {
            $query->whereHas('values.productVariants.product.categories', function ($query) {
                $query->whereIn('categories.id', $this->selectedCategories);
            });

            return $query->whereHas('values.productVariants')->with(['values' => function ($query) {
                $query->whereHas('productVariants.product.categories', function ($query) {
                    if (!empty($this->selectedCategories)) {
                        $query->whereIn('categories.id', $this->selectedCategories);
                    }
                })->orderBy('value');
            }])->get();
        }

        return [];
    }

    public function render()
    {
        $productsQuery = Product::whereHas('variants')->active()->with(['vendor', 'variants', 'variants.attributeValues']);

        // reset the search query if the search is empty
        if ($this->searchQuery == '') {
            $this->search = '';
        }

        if ($this->search) {
            $productsQuery->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('sku', 'like', '%' . $this->search . '%')
                ->orWhereHas('variants', function ($query) {
                    $query->where('sku', 'like', '%' . $this->search . '%');
                });
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
                $query->whereIn('sub_categories.id', $this->selectedSubCategories)
                    ->whereHas('products', function ($query) {
                        $query->where('status', 1)->whereHas('variants');
                    });
            });
        } else {
            $this->productTypes = [];
        }

        if (!empty($this->selectedProductTypes)) {
            $productsQuery->whereHas('productTypes', function ($query) {
                $query->whereIn('product_types.id', $this->selectedProductTypes);
            });
        }

        if (!empty($this->selectedAttributeValues)) {
            $productsQuery->whereHas('variants.attributeValues', function ($query) {
                $query->whereIn('attribute_value_id', $this->selectedAttributeValues);
            });
        }

        return view('livewire.frontend.shop-page')->with([
            'products' => $productsQuery->distinct()->paginate($this->perPage),
        ]);
    }
}
