{{-- Product page / quick add price. Expects $price and $compare_at_price (already multiplied in Customer Mode). --}}
@php
    $grIsCustomerMode = auth('dealer')->check() && auth('dealer')->user()->is_customer_mode_active;
    $grWas = (float) ($compare_at_price ?? 0);
    $grNow = (float) ($price ?? 0);
@endphp
<div class="gr-price-block">
    <span class="gr-price-label">{{ $grIsCustomerMode ? 'Price' : 'Your trade price' }}</span>
    <div class="gr-price-row">
        <span class="gr-price">${{ number_format($grNow, 2) }}</span>
        @if ($grWas > 0 && $grWas > $grNow)
            <s class="gr-price-was">${{ number_format($grWas, 2) }}</s>
        @endif
    </div>
</div>
