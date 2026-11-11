@php
    $grSignedIn = auth()->guard('representative')->check() || auth()->guard('dealer')->check() || auth('web')->check();
    $grCustomerMode = auth('dealer')->check() && auth('dealer')->user()->is_customer_mode_active;
    if ($grCustomerMode) {
        $dealerPercentage = auth('dealer')->user()->fake_sale_percentage;
        $compare_at_price = (float) $compare_at_price * (float) $dealerPercentage;
        $price = (float) $price * (float) $dealerPercentage;
    }
    $grMoney = fn ($n) => '$' . number_format((float) $n, 2);
@endphp
<article class="gr-product-card">
    <a href="{{ route('frontend.product', $product->slug) }}" class="gr-product-media gr-zoom" aria-label="{{ $product->name }}">
        <img src="{{ asset($product->image) }}" loading="lazy" alt="{{ $product->name }}">
    </a>

    @if (isset($variantImages) && count($variantImages) > 1)
        <div class="gr-product-swatches" aria-hidden="true">
            @foreach ($variantImages as $image)
                @if (file_exists($image))
                    <img src="{{ asset($image) }}" loading="lazy" alt="">
                @endif
            @endforeach
        </div>
    @endif

    <div class="gr-product-body">
    <h3 class="gr-product-name">
        <a href="{{ route('frontend.product', $product->slug) }}">{{ $product->name }}</a>
    </h3>

    @if (isVendorActivated() && $product->vendor)
        <p class="gr-product-meta">{{ $product->vendor->name }}</p>
    @endif

    @if ($grSignedIn)
        <div class="gr-product-foot">
            <p class="gr-product-price">
                @if ($hasManyVariants)
                    <span class="gr-product-from">from</span>
                @endif
                @if ($compare_at_price && $compare_at_price > $price)
                    <s class="gr-product-was">{{ $grMoney($compare_at_price) }}</s>
                @endif
                <strong>{{ $grMoney($price) }}</strong>
            </p>
            <div class="gr-product-actions">
                <button type="button" wire:click='openProductOptions()' class="gr-btn gr-btn-dark gr-btn-sm" wire:loading.attr="disabled">
                    <span wire:loading><i class="fas fa-spinner fa-spin" aria-hidden="true"></i></span>
                    <span wire:loading.remove>Quick add</span>
                </button>
                <a href="{{ route('frontend.product', $product->slug) }}" class="gr-btn gr-btn-outline gr-btn-sm">Details</a>
            </div>
        </div>
    @else
        <div class="gr-product-foot">
            <p class="gr-product-login"><a href="{{ route('login') }}">Log in to see trade price</a></p>
            <a href="{{ route('frontend.product', $product->slug) }}" class="gr-btn gr-btn-outline gr-btn-sm w-100">Details</a>
        </div>
    @endif
    </div>
</article>
