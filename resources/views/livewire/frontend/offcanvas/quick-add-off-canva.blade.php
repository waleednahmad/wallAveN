<div>

    <div id="carouselExampleControlsNoTouching" class="carousel slide" data-bs-touch="false" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach ($imagesGallery as $galleryItem)
                <div @class(['carousel-item', 'active' => $loop->first]) data-bs-interval="3000">
                    <img src="{{ $galleryItem->image }}" alt="" loading="lazy" class="d-block h-100"
                        style="object-fit: contain; width: 100%;" alt="...">
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControlsNoTouching"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControlsNoTouching"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <div class="auction-details-content">
        <div class="batch">
            <h6>
                @if (isVendorActivated())
                    {{ $vendor }}
                    <br>
                    <br>
                @endif
                <span style="font-size: 14px; font-weight: 400">
                    SKU: <strong>{{ $selectedSku }}</strong>
                </span>
            </h6>
        </div>
        <h3 class="my-3">
            {{ $product?->name }}
        </h3>
        @if (auth()->guard('representative')->check() || auth()->guard('dealer')->check())
            @if ($compare_at_price && $compare_at_price < $price)
                <span style="text-decoration: line-through; color: #999999">
                    ${{ $price }}
                </span>
                <strong>${{ $compare_at_price }}</strong>
            @else
                <strong>${{ $price }}</strong>
            @endif
        @endif

        @if (isset($groupedAttributes) && count($groupedAttributes))
            <div class="mt-3 quantity-area position-relative">
                @foreach ($groupedAttributes as $attribute => $values)
                    @php
                        $attribute_id = $values['id'];
                        $values = $values['values'];
                    @endphp

                    <h6 class="mt-4 mb-0">{{ $attribute }}</h6>
                    <div class="mb-3 values-container">
                        @forelse($values as $value)
                            <span @class(['active' => in_array($value['id'], $selectedAttributeValues)])
                                wire:click='selectAttributeValue("{{ $attribute_id }}", "{{ $value['id'] }}")'>
                                {{ $value['value'] }}
                            </span>
                        @empty
                            <p>No values found</p>
                        @endforelse
                    </div>
                @endforeach
                {{-- loading spinner --}}
                <div wire:loading>
                    <div class="loading-spinner"
                        style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; justify-content: center; align-items: center; background-color: rgba(255, 255, 255, 0.8);">
                        <div class="spinner-border text-secondary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($variantNotFound)
            <div class="alert alert-warning" style="text-align: center;" wire:tranistion>
                No variant found for the selected attributes. Please try different combinations.
                <br>
                <button wire:click="setDefaultAttributeValues" class="mt-2 btn btn-sm btn-secondary">
                    Reset
                </button>
            </div>
        @endif

        @if (auth()->guard('representative')->check() || auth()->guard('dealer')->check())
            {{-- ========== Add To Cart ========= --}}
            <div class="add-to-cart">
                @if (!$variantNotFound)
                    <div style="display: flex; gap: 10px; align-items: center;" wire:tranistion>
                        <button class="btn" wire:click="decreaseQuantity"
                            style="background-color: #f5f5f5; color: #000;">-</button>
                        <input type="number" style="min-width: max-content; padding: 5px; border: none"
                            wire:model="quantity" min="1">
                        <button class="btn" wire:click="increaseQuantity"
                            style="background-color: #f5f5f5; color: #000;">+</button>
                    </div>
                @endif
                {{-- <button class="mt-3 btn" wire:click="addToCart" @disabled($variantNotFound)
                    style="background-color: #000; color: #fff; max-width: fit-content; padding: 10px 20px;">
                    Add to Cart
                </button> --}}
            </div>
            {{-- ========== End Add To Cart ========= --}}
        @endif
    </div>
</div>
