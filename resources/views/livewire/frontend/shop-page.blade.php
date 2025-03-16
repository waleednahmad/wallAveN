<div class="auction-card-sidebar-section pt-120 mb-120">
    <div class="container">
        <div class="row gy-5">
            <div class="order-1 col-xl-3 order-xl-1">
                <div class="sidebar-area" style="position: sticky; top: 20px;">
                    <div class="single-widgets widget_search mb-50">
                        <form>
                            <div class="wp-block-search__inside-wrapper ">
                                <input type="search" id="wp-block-search__input-1" class="wp-block-search__input"
                                    name="s" value="" placeholder="Search Product" required=""
                                    wire:model.live="search">
                            </div>
                        </form>
                    </div>
                    <div class="single-widgets mb-50"
                        style="max-height: 450px; overflow-y: auto; overflow-x: hidden; position: relative;">
                        {{-- $this->search = '';
                        $this->selectedCategories = [];
                        $this->selectedSubCategories = [];
                        $this->selectedProductTypes = [];
                        $this->productTypes = [];
                        $this->subCategories = []; --}}
                        @if (
                            (isset($search) && $search != '') ||
                                (isset($selectedCategories) && count($selectedCategories) > 0) ||
                                (isset($selectedSubCategories) && count($selectedSubCategories) > 0) ||
                                (isset($selectedProductTypes) && count($selectedProductTypes) > 0))
                            <button class="btn btn-danger btn-sm" wire:click="clearAllFilters()" wire:tranisition
                                title="Clear All Filters"
                                style="position: absolute; top: 0; right: 10px; z-index: 1000;">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                        <div class="widget-title">
                            <h5>Categories</h5>
                        </div>
                        <div class="checkbox-container">
                            <ul>
                                @foreach ($this->categories as $category)
                                    <li>
                                        <label @class([
                                            'filter-container-label',
                                            'active' => in_array($category->id, $selectedCategories),
                                        ])
                                            wire:click="setProperty('selectedCategories', '{{ $category->id }}')">
                                            <span class="check-icon">
                                                @if (in_array($category->id, $selectedCategories))
                                                    <i class="fas fa-check"></i>
                                                @endif
                                            </span>

                                            <span>{{ $category->name }} </span>
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <hr>
                        @if (isset($subCategories) && count($subCategories) > 0)
                            <div class="widget-title">
                                <h5>Sub Categories</h5>
                            </div>
                            <div class="checkbox-container">
                                <ul>
                                    @foreach ($this->subCategories as $subCategory)
                                        <li>
                                            <label @class([
                                                'filter-container-label',
                                                'active' => in_array($subCategory->id, $selectedSubCategories),
                                            ])
                                                wire:click="setProperty('selectedSubCategories', '{{ $subCategory->id }}')">
                                                <span class="check-icon">
                                                    @if (in_array($subCategory->id, $selectedSubCategories))
                                                        <i class="fas fa-check"></i>
                                                    @endif
                                                </span>

                                                <span>
                                                    {{ $subCategory->name }}
                                                </span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <hr>
                        @endif
                        @if (isset($productTypes) && count($productTypes) > 0)

                            <div class="widget-title">
                                <h5>Product Types</h5>
                            </div>
                            <div class="checkbox-container">
                                <ul>
                                    @foreach ($this->productTypes as $productType)
                                        <li>
                                            <label @class([
                                                'filter-container-label',
                                                'active' => in_array($productType->id, $selectedProductTypes),
                                            ])
                                                wire:click="setProperty('selectedProductTypes', '{{ $productType->id }}')">
                                                <span class="check-icon">
                                                    @if (in_array($productType->id, $selectedProductTypes))
                                                        <i class="fas fa-check"></i>
                                                    @endif
                                                </span>
                                                <span>
                                                    {{ $productType->name }}
                                                </span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <hr>
                        @endif
                    </div>
                </div>
            </div>
            <div class="order-1 col-xl-9 order-xl-2">
                <div class="row">
                    <div class="col-lg-12 mb-30">
                        <div class="auction-card-top-area">
                            <div class="left-content">
                                <h6>
                                    Showing
                                    <span>
                                        {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }}
                                    </span>
                                    of
                                    <span>
                                        {{ $products->total() }}
                                    </span>
                                    results
                                </h6>
                            </div>
                            {{-- <div class="right-content">
                                <div class="category-area d-lg-flex d-none active">
                                    <select>
                                        <option value="1">sorting</option>
                                        <option value="2">latest </option>
                                        <option value="2">Best selling </option>
                                        <option value="2">Price Low to high </option>
                                        <option value="2">Price high to low </option>
                                    </select>
                                </div> 
                                <ul class="grid-view d-lg-flex d-none">
                                    <li class="column-2">
                                        <svg width="7" height="14" viewBox="0 0 7 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.75 13.1875L0.749999 0.8125M5.8125 13.1875L5.8125 0.8125"
                                                stroke="#A0A0A0" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </li>
                                    <li class="column-3 active">
                                        <svg width="10" height="14" viewBox="0 0 10 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M1.0625 13.1875L1.0625 0.8125M5 13.1875L5 0.8125M8.9375 13.1875L8.9375 0.8125"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </li>
                                </ul> 
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="list-grid-product-wrap">
                    <div class="row gy-4">
                        @forelse ($products as $productItem)
                            <div class="col-lg-4 col-md-6 item" wire:key="{{ $productItem->id }}-card">
                                <livewire:frontend.components.product-card-component :product="$productItem"
                                    :key="$productItem->id" />
                            </div>
                        @empty
                            <div class="col-lg-12">
                                <div class="text-center alert alert-warning" role="alert">
                                    No products found.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
                @if ($products->hasMorePages())
                    <div x-intersect="$wire.loadMore()" class="mt-5 d-flex align-items-center justify-content-center">
                        <button class="custom-button" wire:loading.attr="disabled" wire:target="loadMore">
                            <span wire:loading wire:target="loadMore" class="spinner-border spinner-border-sm"
                                role="status" aria-hidden="true"></span>
                            Load More
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
