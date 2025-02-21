<div class="auction-card-sidebar-section pt-120 mb-120">
    <div class="container">
        <div class="row gy-5">
            <div class="order-1 col-xl-3 order-xl-1">
                <div class="sidebar-area">
                    <div class="single-widgets widget_search mb-50">
                        <form>
                            <div class="wp-block-search__inside-wrapper ">
                                <input type="search" id="wp-block-search__input-1" class="wp-block-search__input"
                                    name="s" value="" placeholder="Search Product" required=""
                                    wire:model.live="search">
                                <button type="submit" class="wp-block-search__button">
                                    <svg width="16" height="16" viewBox="0 0 16 16"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.7425 10.3418C12.7107 9.0205 13.1444 7.38236 12.9567 5.75508C12.769 4.1278 11.9739 2.63139 10.7303 1.56522C9.48666 0.49905 7.88635 -0.0582469 6.2495 0.0048239C4.61265 0.0678947 3.05997 0.746681 1.90209 1.90538C0.744221 3.06409 0.0665459 4.61725 0.00464636 6.25415C-0.0572531 7.89104 0.501188 9.49095 1.56825 10.7338C2.63531 11.9766 4.13229 12.7707 5.7597 12.9572C7.38711 13.1438 9.02494 12.7089 10.3455 11.7397H10.3445C10.3745 11.7797 10.4065 11.8177 10.4425 11.8547L14.2924 15.7046C14.4799 15.8922 14.7342 15.9977 14.9995 15.9977C15.2647 15.9978 15.5192 15.8926 15.7068 15.7051C15.8944 15.5176 15.9999 15.2632 16 14.9979C16.0001 14.7327 15.8948 14.4782 15.7073 14.2906L11.8575 10.4408C11.8217 10.4046 11.7833 10.3711 11.7425 10.3408V10.3418ZM12.0004 6.4979C12.0004 7.22015 11.8582 7.93532 11.5818 8.60258C11.3054 9.26985 10.9003 9.87614 10.3896 10.3868C9.87889 10.8975 9.2726 11.3027 8.60533 11.5791C7.93807 11.8554 7.2229 11.9977 6.50065 11.9977C5.77841 11.9977 5.06324 11.8554 4.39597 11.5791C3.72871 11.3027 3.12242 10.8975 2.61171 10.3868C2.10101 9.87614 1.6959 9.26985 1.41951 8.60258C1.14312 7.93532 1.00086 7.22015 1.00086 6.4979C1.00086 5.03927 1.5803 3.64037 2.61171 2.60896C3.64312 1.57755 5.04202 0.99811 6.50065 0.99811C7.95929 0.99811 9.35818 1.57755 10.3896 2.60896C11.421 3.64037 12.0004 5.03927 12.0004 6.4979Z" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="single-widgets mb-50">
                        {{-- <div class="widget-title">
                            <h5>Types</h5>
                        </div>
                        <div class="checkbox-container">
                            <ul>
                                @foreach ($this->productTypes as $productType)
                                    <li>
                                        <label class="containerss">
                                            <input type="checkbox" wire:model="selectedType" value="{{ $productType }}"
                                                wire:change='setProperty("types", "{{ $productType }}")'
                                                {{ in_array($productType, $properties['types']) ? 'checked' : '' }}>
                                            <span class="checkmark"></span>
                                            <span>{{ $productType }}</span>
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <hr> --}}

                        <div class="widget-title">
                            <h5>Categories</h5>
                        </div>
                        <div class="checkbox-container"
                            style="max-height: 300px; overflow-y: auto; overflow-x: hidden;">
                            <ul>
                                @foreach ($this->categories as $category => $count)
                                    <li>
                                        <label class="containerss">
                                            <input type="checkbox" wire:model="selectedType" value="{{ $category }}"
                                                wire:change='setProperty("tags", "{{ $category }}")'
                                                {{ in_array($category, $properties['tags']) ? 'checked' : '' }}>
                                            <span class="checkmark"></span>
                                            <span>{{ $category }} ({{ $count }})</span>
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <hr>
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
                        @forelse ($products as $product)
                            <div class="col-lg-4 col-md-6 item " wire:key="{{ $product->sku }}">
                                <div class="auction-card general-art">
                                    <div class="auction-card-img-wrap"
                                        style="height: 200px; overflow: hidden; border-radius: 10px;">
                                        <a href="{{ route('frontend.product', $product->handle) }}" class="card-img">
                                            <img src="{{ $product->image_src }}" loading="lazy"
                                                style="object-fit: contain; width: 100%; height: 100%;"
                                                alt="{{ $product->image_alt_text ?? $product->title }}">
                                        </a>
                                    </div>
                                    <div class="auction-card-content">
                                        <h6>
                                            <a href="{{ route('frontend.product', $product->handle) }}">
                                                {{ $product->title }}
                                            </a>
                                        </h6>
                                        <ul>
                                            <li>
                                                <span>
                                                    Vendor :
                                                </span>
                                                {{ $product->vendor }}
                                            </li>
                                            @auth('dealer')
                                                <li><span>Price : </span>
                                                    <strong>${{ $product->variant_price }}</strong>
                                                </li>
                                            @endauth
                                        </ul>
                                        <a href="{{ route('frontend.product', $product->handle) }}"
                                            class="bid-btn btn-hover">
                                            <span>
                                                View Details
                                            </span>
                                            <strong></strong>
                                        </a>
                                    </div>
                                </div>
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
