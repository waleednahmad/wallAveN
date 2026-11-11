@php
    // Customer Mode shows the dealer's retail prices. Apply the multiplier once
    // (the old template multiplied the order total again for every line).
    $grMultiplier = 1;
    if (auth('dealer')->check() && auth('dealer')->user()->is_customer_mode_active) {
        $grMultiplier = (float) auth('dealer')->user()->fake_sale_percentage ?: 1;
    }
    $grMoney = fn ($n) => '$' . number_format((float) $n, 2);
    $grCount = collect($cartTemps)->sum('quantity');
    $grOrderingFor = auth('representative')->user()?->buyingFor ?? auth('web')->user()?->buyingFor;
@endphp
<div class="gr-cart">
    @if ($grOrderingFor)
        <div style="margin: 12px 0 4px; padding: 12px 14px; background: var(--gr-linen); border-radius: 12px; font-size: 14px;">
            Ordering for <strong>{{ $grOrderingFor->company_name ?? $grOrderingFor->name }}</strong>
            · <a href="#" class="gr-link" data-bs-toggle="modal" data-bs-target="#DealerSelection">Change</a>
        </div>
    @endif

    @forelse ($cartTemps as $item)
        @php
            $values = null;
            if ($item->item_type == 'variant') {
                $attributes = json_decode($item->attributes, true) ?: [];
                $filteredAttributes = array_filter($attributes, fn ($value) => strtolower((string) $value) !== 'none');
                $values = implode(' · ', array_map('ucwords', array_values($filteredAttributes)));
                // Sizes are in feet (5' x 8'), matching the shop and product pages.
                $values = str_replace('"', "'", $values);
            }
            $itemPrice = (float) $item->price * $grMultiplier;
            $itemTotal = (float) $item->total * $grMultiplier;
        @endphp
        <div class="gr-cart-item" wire:key="cart-{{ $item->id }}">
            <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
            <div class="gr-cart-body">
                <div class="d-flex justify-content-between" style="gap: 8px;">
                    <p class="gr-cart-name">{{ $item->name }}</p>
                    <strong style="font-weight: 600; white-space: nowrap;">{{ $grMoney($itemTotal) }}</strong>
                </div>
                @if ($values)
                    <p class="gr-cart-meta">{{ $values }}</p>
                @endif
                <p class="gr-cart-meta">{{ $grMoney($itemPrice) }} each @if ($item->sku) · SKU {{ $item->sku }} @endif</p>
                <div class="d-flex justify-content-between align-items-center" style="margin-top: auto; padding-top: 6px;">
                    <div class="gr-qty">
                        <button type="button" wire:click="decreaseQuantity('{{ $item->id }}')" wire:loading.attr="disabled" aria-label="Decrease quantity of {{ $item->name }}">−</button>
                        <span aria-live="polite">{{ $item->quantity }}</span>
                        <button type="button" wire:click="increaseQuantity('{{ $item->id }}')" wire:loading.attr="disabled" aria-label="Increase quantity of {{ $item->name }}">+</button>
                    </div>
                    <button type="button" class="gr-text-btn" wire:click="removeFromCart('{{ $item->id }}')" wire:loading.attr="disabled">Remove</button>
                </div>
            </div>
        </div>
    @empty
        <div style="padding: 56px 0; text-align: center;">
            <p style="font-family: var(--gr-font-display); font-size: 24px; margin: 0 0 8px;">Your cart is empty</p>
            <p style="color: var(--gr-muted); margin: 0 0 20px;">Browse the catalog to add rugs and furniture.</p>
            <a href="{{ route('frontend.shop') }}" class="gr-btn gr-btn-outline gr-btn-sm">Back to the catalog</a>
        </div>
    @endforelse

    @if (count($cartTemps))
        <div class="gr-cart-total">
            <div class="d-flex justify-content-between" style="font-size: 16px; margin-bottom: 4px;">
                <span style="color: var(--gr-muted);">Subtotal ({{ $grCount }} {{ $grCount == 1 ? 'item' : 'items' }})</span>
                <span>{{ $grMoney((float) $total * $grMultiplier) }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-baseline" style="margin-bottom: 6px;">
                <span style="font-weight: 600;">Order total</span>
                <span class="gr-amount">{{ $grMoney((float) $total * $grMultiplier) }}</span>
            </div>
            <p style="margin: 0 0 16px; font-size: 12px; color: var(--gr-muted);">Shipping is arranged after the order is confirmed.</p>
            <button type="button" class="gr-btn gr-btn-primary w-100" wire:click="checkout" wire:loading.attr="disabled">
                <span wire:loading wire:target="checkout"><i class="fas fa-spinner fa-spin" aria-hidden="true"></i></span>
                Continue to checkout <i class="fas fa-arrow-right gr-btn-arrow" aria-hidden="true"></i>
            </button>
        </div>
    @endif
</div>
