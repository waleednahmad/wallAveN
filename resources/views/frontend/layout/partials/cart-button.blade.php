{{-- Header cart icon with a live item count. Expects $grCartCount; the count
     updates from the 'cart-count' event the cart offcanvas sends on every render. --}}
<a href="#" class="wishlist-btn gr-cart-btn {{ $class ?? '' }}" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanva"
    aria-controls="cartOffcanva"
    x-data="{ count: {{ $grCartCount }} }"
    x-on:cart-count.window="if ($event.detail.count !== count) { count = $event.detail.count; $nextTick(() => { $refs.badge?.classList.remove('gr-bump'); void $refs.badge?.offsetWidth; $refs.badge?.classList.add('gr-bump'); }); }"
    x-bind:aria-label="count ? 'Cart, ' + count + (count === 1 ? ' item' : ' items') : 'Cart'"
    aria-label="Cart{{ $grCartCount ? ', ' . $grCartCount . ($grCartCount === 1 ? ' item' : ' items') : '' }}">
    <span class="gr-cart-badge" x-ref="badge" x-show="count > 0" x-text="count > 99 ? '99+' : count" @if (!$grCartCount) style="display: none;" @endif>{{ $grCartCount > 99 ? '99+' : $grCartCount }}</span>
    <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path
            d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm0 2c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm10-2c-1.1 0-1.99.9-1.99 2S15.9 22 17 22s2-.9 2-2-.9-2-2-2zm0 2c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zM7.82 14.18l.03-.12L9.1 10H19c.55 0 1-.45 1-1s-.45-1-1-1H9.42l-.94-4.34C8.39 3.26 7.74 3 7.05 3H4c-.55 0-1 .45-1 1s.45 1 1 1h2.05l1.1 5.06L5.6 14.59c-.09.26-.14.54-.14.83 0 1.1.9 2 2 2h12c.55 0 1-.45 1-1s-.45-1-1-1H7.82z" />
    </svg>
</a>
