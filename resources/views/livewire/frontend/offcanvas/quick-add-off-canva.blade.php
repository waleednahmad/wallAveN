<div>

    {{-- Gallery: the main image follows the selected color/size (Livewire sets $image);
         thumbnails let the dealer look at other photos. No auto-sliding. --}}
    @php
        $grMainImage = $image ?: ($product?->image ?? null);
        $grThumbs = collect([$grMainImage])->merge(collect($imagesGallery ?? [])->pluck('image'))->filter()->unique()->values();
    @endphp
    @if ($grMainImage || $grThumbs->count())
        <div class="gr-qa-gallery" wire:key="qa-gallery-{{ md5((string) $grMainImage) }}"
            x-data="{ shown: @js($grMainImage ? asset($grMainImage) : asset($grThumbs->first())) }">
            <div class="gr-qa-main">
                <img x-bind:src="shown" src="{{ $grMainImage ? asset($grMainImage) : asset($grThumbs->first()) }}" alt="{{ $product?->name }}">
            </div>
            @if ($grThumbs->count() > 1)
                <div class="gr-qa-thumbs">
                    @foreach ($grThumbs as $thumb)
                        <button type="button" x-on:click="shown = @js(asset($thumb))"
                            x-bind:class="shown === @js(asset($thumb)) ? 'is-active' : ''"
                            aria-label="Show photo {{ $loop->iteration }}">
                            <img src="{{ thumb($thumb, 240) }}" alt="" loading="lazy" decoding="async">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <div class="auction-details-content">
        <div class="batch">
            @if (isVendorActivated() && $vendor)
                <p class="gr-vendor">{{ $vendor }}</p>
            @endif
            <p class="gr-sku">SKU <span>{{ $selectedSku }}</span></p>
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
            @include('livewire.frontend.partials.price-block')
        @endif
        {{-- {{ $compare_at_price }} : {{ $price }} --}}
        @php
            // Check if any attribute is named 'None' and its value is also 'None'
            $hasNoneAttribute = false;
            if (isset($groupedAttributes) && count($groupedAttributes)) {
                foreach ($groupedAttributes as $attribute => $values) {
                    if (strtolower($attribute) === 'none') {
                        foreach ($values['values'] as $value) {
                            if (strtolower($value['value']) === 'none') {
                                $hasNoneAttribute = true;
                                break 2;
                            }
                        }
                    }
                }
            }
        @endphp
        @if (isset($groupedAttributes) && count($groupedAttributes) && !$hasNoneAttribute)
            <div class="mt-3 quantity-area position-relative" wire:loading.class="gr-busy" wire:target="selectAttributeValue,setDefaultAttributeValues">
                            {{-- Light loading state while the variant updates (the old white overlay covered the whole page) --}}
                            <div class="gr-updating" wire:loading.flex wire:target="selectAttributeValue,setDefaultAttributeValues" role="status">
                                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Updating
                            </div>
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
                                {{ trim($attribute) === 'Size' ? str_replace('"', "'", $value['value']) : $value['value'] }}
                            </span>
                        @empty
                            <p>No values found</p>
                        @endforelse
                    </div>
                @endforeach
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
                    <div class="gr-buy-row">
                        <div class="gr-qty gr-qty-lg" role="group" aria-label="Quantity">
                            <button type="button" wire:click="decreaseQuantity" aria-label="Decrease quantity" @disabled($quantity <= 1)>−</button>
                            <input type="number" inputmode="numeric" wire:model="quantity" min="1" aria-label="Quantity">
                            <button type="button" wire:click="increaseQuantity" aria-label="Increase quantity">+</button>
                        </div>
                        <button type="button" class="gr-btn gr-btn-primary gr-buy-btn" wire:click="addToCart" wire:loading.attr="disabled" wire:target="addToCart" @disabled($variantNotFound)>
                            <span wire:loading wire:target="addToCart" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                            <i class="fas fa-shopping-bag" wire:loading.remove wire:target="addToCart" aria-hidden="true"></i>
                            Add to cart
                        </button>
                    </div>
                @endif
            </div>
            {{-- ========== End Add To Cart ========= --}}
        @endif
    </div>
</div>
