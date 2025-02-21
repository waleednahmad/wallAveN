<?php

namespace App\Livewire\Frontend;

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
        'types' => [],
        'tags' => [],
    ];





    public function loadMore()
    {
        $this->perPage += 9;
    }

    public function setProperty($key, $value)
    {

        switch ($key) {
                // -------- types --------
            case 'types':
                if (in_array($value, $this->properties['types'])) {
                    $this->properties['types'] = array_diff($this->properties['types'], [$value]);
                } else {
                    $this->properties['types'][] = $value;
                }
                break;

                // -------- tags --------
            case 'tags':
                if (in_array($value, $this->properties['tags'])) {
                    $this->properties['tags'] = array_diff($this->properties['tags'], [$value]);
                } else {
                    $this->properties['tags'][] = $value;
                }
                break;
        }
    }


    // ------------------------------
    // Computed Properties
    // ------------------------------
    #[Computed()]
    public function productTypes()
    {
        return Product::select('type')->whereNotNull('type')->distinct()->get()->pluck('type');
    }

    #[Computed()]
    public function categories()
    {
        return Product::select('tags', DB::raw('count(*) as product_count'))
            ->whereNotNull('tags')
            ->groupBy('tags')
            ->orderBy('tags')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->tags => $item->product_count];
            });
    }


    public function render()
    {
        $productsQuery = Product::whereNotNull('title');

        if ($this->search) {
            $productsQuery->where('title', 'like', '%' . $this->search . '%');
        }

        if ($this->properties['types']) {
            $productsQuery->whereIn('type', $this->properties['types']);
        }

        if ($this->properties['tags']) {
            $productsQuery->whereIn('tags', $this->properties['tags']);
        }


        return view('livewire.frontend.shop-page')->with([
            'products' => $productsQuery->paginate($this->perPage),
        ]);
    }
}
