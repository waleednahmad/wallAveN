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
                    SKU: <strong>{{ ucwords(strtolower($selectedSku)) }}</strong>
                </span>
            </h6>
        </div>
        <h3 class="my-3">
            {{ $product?->name }}
        </h3>
        @auth('dealer')
            @if (auth('dealer')->user()->is_customer_mode_active)
                @php
                    $dealerPercentage = auth('dealer')->user()->fake_sale_percentage;
                    $compare_at_price = (float) $compare_at_price * (float) $dealerPercentage;
                    $price = (float) $price * (float) $dealerPercentage;
                @endphp
            @endif
        @endauth
        @if (auth()->guard('representative')->check() || auth()->guard('dealer')->check() || auth('web')->check())
            @if ($compare_at_price && $compare_at_price > $price && $compare_at_price > 0)
                <span style="text-decoration: line-through; color: #999999">
                    ${{ $compare_at_price }}
                </span>
                <strong>
                    ${{ $price }}
                </strong>
            @else
                <strong>${{ $price }}</strong>
            @endif
        @endif
        {{-- {{ $compare_at_price }} : {{ $price }} --}}
        @php
            $haveNoneValue = false;
        @endphp
        @if (isset($groupedAttributes) && count($groupedAttributes) && $haveNoneValue)
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
                            @if ($value['value'] == 'None')
                                @php
                                    $haveNoneValue = true;
                                @endphp
                            @endif
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

        @if (auth()->guard('representative')->check() || auth()->guard('dealer')->check() || auth('web')->check())
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
                    <!-- Notification message (hidden by default) -->
                    <div x-data="{ showNotification: false }"
                        x-on:notify.window="showNotification = true; setTimeout(() => showNotification = false, 4000);"
                        x-show="showNotification" style="display: none;"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform translate-y-4"
                        class="bottom-0 p-3 m-3 text-white rounded shadow bg-success position-relative">
                        <span>
                            Item added to cart!
                        </span>
                        <br>
                        <a href="javascript:void(0)" wire:click='previewCart()'>
                            <span style="color: #fff; text-decoration: underline">
                                View Cart
                            </span>
                        </a>
                        <button @click="showNotification = false" class="btn"
                            style="background: none; border: none; color: white; font-size: 1.2em; position: absolute; top: 10px; right: 10px;">
                            x
                        </button>
                    </div>
                    <button class="mt-3 btn" wire:click="addToCart" @disabled($variantNotFound)
                        style="background-color: #000; color: #fff; max-width: fit-content; padding: 10px 20px;">
                        Add to Cart
                    </button>
                @endif
            </div>
            {{-- ========== End Add To Cart ========= --}}
        @endif
    </div>
</div>
