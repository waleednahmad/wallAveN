<div class="auction-card general-art">
    <div class="auction-details-section mb-120">

        <div class="auction-card-img-wrap" style="height: 200px; overflow: hidden; border-radius: 10px;">
            <a href="{{ route('frontend.product', $product->slug) }}" class="card-img">
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
                @if (auth()->guard('representative')->check() || auth()->guard('dealer')->check())
                    <li><span>Price : </span>
                        <strong>${{ $product->variant_price }}</strong>
                    </li>
                @endif
            </ul>
            <div class="d-flex align-items-center" style="gap: 10px;">
                {{-- Details --}}
                <a href="{{ route('frontend.product', $product->slug) }}"
                    class="px-3 py-2 text-center bid-btn btn-hover w-100 d-flex align-items-center justify-content-center">
                    <span style="font-size: 14px; font-weight: 400">
                        View Details
                    </span>
                    <strong></strong>
                </a>
                @if (auth()->guard('representative')->check() || auth()->guard('dealer')->check())
                    {{-- + Quick add --}}
                    <a href="javascript:void(0)" wire:click='openProductOptions()'
                        class="px-3 py-2 text-center primary-btn2 btn-hover w-100 d-flex align-items-center justify-content-center"
                        {{-- wire:click="$emit('openModal', 'frontend.add-to-cart-modal', {{ json_encode(['product' => $product]) }})" --}}>
                        <span style="font-size: 14px; font-weight: 400">
                            <i class="fas fa-plus"></i> Quick Add
                        </span>
                        <strong></strong>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
