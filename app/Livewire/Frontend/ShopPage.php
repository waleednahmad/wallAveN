<?php

namespace App\Livewire\Frontend;

use App\Models\Category;
use App\Models\Product;
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
    public $properties  = [
        'categories' => [],
        'subCategories' => [],
    ];
    public $subCategories = [];

    public function loadMore()
    {
        $this->perPage += 9;
    }

    public function setProperty($key, $value)
    {
        switch ($key) {
            // -------- categories --------
            case 'categories':
                if (in_array($value, $this->properties['categories'])) {
                    $this->properties['categories'] = array_diff($this->properties['categories'], [$value]);
                } else {
                    $this->properties['categories'][] = $value;
                }

                // // if there is no category selected, reset the subcategories
                // if (empty($this->properties['categories'])) {
                //     $this->subCategories = [];
                // } else {
                //     $this->subCategories = Category::whereIn('id', $this->properties['categories'])
                //         ->with(['subCategories'])->get()->pluck('subCategories')->flatten();
                // }
                break;

            // -------- sub categories --------
            case 'subCategories':
                if (in_array($value, $this->properties['subCategories'])) {
                    $this->properties['subCategories'] = array_diff($this->properties['subCategories'], [$value]);
                } else {
                    $this->properties['subCategories'][] = $value;
                }
                break;

            // -------- types --------
            case 'types':
                if (in_array($value, $this->properties['types'])) {
                    $this->properties['types'] = array_diff($this->properties['types'], [$value]);
                } else {
                    $this->properties['types'][] = $value;
                }
                break;
        }
    }

    // ------------------------------
    // Computed Properties
    // ------------------------------
    #[Computed()]
    public function categories()
    {
        return Category::active()->whereHas('products', function ($query) {
            $query->where('status', 1);
        })->withCount(['products' => function ($query) {
            $query->where('status', 1);
        }])->get();
    }

    public function render()
    {
        $productsQuery = Product::active()->with(['vendor']);

        if ($this->search) {
            $productsQuery->where('name', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->properties['categories'])) {
            $productsQuery->whereHas('categories', function ($query) {
                $query->whereIn('categories.id', $this->properties['categories']);
            });
        } else {
            $this->properties['subCategories'] = [];
            $this->subCategories = [];
        }

        // if (!empty($this->properties['subCategories'])) {
        //     $productsQuery->whereHas('subCategories', function ($query) {
        //         $query->whereIn('sub_categories.id', $this->properties['subCategories']);
        //     });
        // }

        $products = $productsQuery->distinct()->paginate($this->perPage);

        return view('livewire.frontend.shop-page')->with([
            'products' => $products,
        ]);
    }
}
