<div class="auction-card general-art">
    <div class="auction-details-section">

        <div class="auction-card-img-wrap" style="height: 200px; overflow: hidden; border-radius: 10px;">
            <a href="{{ route('frontend.product', $product->slug) }}" class="card-img h-100">
                <img src="{{ asset($product->image) }}" loading="lazy"
                    style="object-fit: contain; width: 100%; height: 100%;" alt="{{ $product->name }}">
            </a>
        </div>
        <div class="auction-card-content">
            <h6>
                <a href="{{ route('frontend.product', $product->slug) }}">
                    {{ $product->name }}
                    <span wire:loading>
                        loading <i class="fas fa-spinner fa-spin"></i>
                    </span>
                </a>
            </h6>
            <ul>
                @if (isVendorActivated())
                    @if ($product->vendor)
                        <li>
                            <span>
                                Vendor :
                            </span>
                            {{ $product->vendor ? $product->vendor->name : '-' }}
                        </li>
                    @else
                        <li class="d-block" style="height: 25px">
                        </li>
                    @endif
                @endif
                @if (auth()->guard('representative')->check() || auth()->guard('dealer')->check())
                    <li>
                        @if ($hasManyVariants)
                            <span>From </span>
                        @endif
                        @if ($compare_at_price && $compare_at_price < $price)
                            <span style="text-decoration: line-through; color: #999999">
                                ${{ $price }}
                            </span>
                            <strong>${{ $compare_at_price }}</strong>
                        @else
                            <strong>${{ $price }}</strong>
                        @endif
                    </li>
                @endif
            </ul>
            <div class="d-flex align-items-center" style="gap: 10px;">
                {{-- Details --}}
                <a href="{{ route('frontend.product', $product->slug) }}" class="custom-white-btn">
                    <span>
                        View Details
                    </span>
                </a>
                @if (auth()->guard('representative')->check() || auth()->guard('dealer')->check())
                    {{-- + Quick add --}}
                    <button wire:click='openProductOptions()' class="custom-black-btn" wire:loading.attr="disabled"
                        {{-- wire:click="$emit('openModal', 'frontend.add-to-cart-modal', {{ json_encode(['product' => $product]) }})" --}}>
                        <span>
                            <i class="fas fa-plus"></i> Quick Add
                        </span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
