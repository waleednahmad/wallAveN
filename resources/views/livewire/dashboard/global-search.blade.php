<div class="adm-gsearch" x-data="{ open: false }" x-on:click.outside="open = false" x-on:keydown.escape="open = false; $refs.input.blur()"
    x-on:keydown.window.cmd.k="$event.preventDefault(); $refs.input.focus(); open = true" x-on:keydown.window.ctrl.k="$event.preventDefault(); $refs.input.focus(); open = true">
    <label class="adm-gsearch-box">
        <i class="fas fa-search" aria-hidden="true"></i>
        <input x-ref="input" type="search" wire:model.live.debounce.300ms="q" x-on:focus="open = true" x-on:input="open = true"
            placeholder="Search orders, dealers, products…" aria-label="Search orders, dealers and products" autocomplete="off">
        <kbd class="adm-gsearch-kbd" aria-hidden="true">⌘K</kbd>
        <span wire:loading wire:target="q" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
    </label>

    @if (mb_strlen($term) >= 2)
        <div class="adm-gsearch-panel" x-show="open" x-transition.opacity style="display: none;" role="listbox">
            @if ($orders->isEmpty() && $dealers->isEmpty() && $products->isEmpty())
                <p class="adm-gsearch-empty">Nothing found for “{{ $term }}”.</p>
            @endif

            @if ($orders->isNotEmpty())
                <p class="adm-gsearch-group">Orders</p>
                @foreach ($orders as $order)
                    <a class="adm-gsearch-item" href="{{ route('dashboard.orders.index', ['order' => $order->id]) }}">
                        <span class="adm-gsearch-ico"><i class="fas fa-receipt" aria-hidden="true"></i></span>
                        <span class="adm-gsearch-main">#{{ $order->id }} · {{ $order->dealer?->company_name ?: $order->dealer?->name ?: 'Unknown dealer' }}
                            <span class="adm-gsearch-sub">{{ ucfirst($order->status) }} · ${{ number_format((float) $order->total, 2) }} · {{ $order->created_at?->format('M j, Y') }}</span>
                        </span>
                    </a>
                @endforeach
            @endif

            @if ($dealers->isNotEmpty())
                <p class="adm-gsearch-group">Dealers</p>
                @foreach ($dealers as $dealer)
                    @php
                        $state = $dealer->is_approved ? 'Approved' : ($dealer->rejected_at ? 'Declined' : 'Application waiting');
                    @endphp
                    <a class="adm-gsearch-item" href="{{ route('dashboard.dealers.index', $dealer->is_approved || $dealer->rejected_at ? ['dealer' => $dealer->id] : ['tab' => 'applications']) }}">
                        <span class="adm-gsearch-ico"><i class="fas fa-store" aria-hidden="true"></i></span>
                        <span class="adm-gsearch-main">{{ $dealer->company_name ?: $dealer->name }}
                            <span class="adm-gsearch-sub">{{ $dealer->email }} · {{ $state }}</span>
                        </span>
                    </a>
                @endforeach
            @endif

            @if ($products->isNotEmpty())
                <p class="adm-gsearch-group">Products</p>
                @foreach ($products as $product)
                    <a class="adm-gsearch-item" href="{{ route('dashboard.products.edit', $product->id) }}">
                        <span class="adm-gsearch-ico">
                            @if ($product->image)
                                <img src="{{ thumb($product->image, 240) }}" alt="" loading="lazy">
                            @else
                                <i class="fas fa-box" aria-hidden="true"></i>
                            @endif
                        </span>
                        <span class="adm-gsearch-main">{{ $product->name }}
                            <span class="adm-gsearch-sub">SKU {{ $product->sku ?: '—' }} · {{ $product->status ? 'Active' : 'Hidden' }}</span>
                        </span>
                    </a>
                @endforeach
            @endif
        </div>
    @endif
</div>
