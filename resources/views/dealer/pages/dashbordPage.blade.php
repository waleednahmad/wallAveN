@php
    $grDealer = auth('dealer')->user();
    $grStatusColors = [
        'pending' => '#C9973F',
        'processing' => '#44607F',
        'completed' => '#3E7D5B',
        'canceled' => '#9A9184',
        'declined' => '#9A9184',
    ];
    $grRecentOrders = $grDealer->orders()->withCount('orderItems')->latest()->take(4)->get();
@endphp
<div class="dashboard-content-wrap gr-dealer">
    <div class="d-flex flex-wrap justify-content-between align-items-end" style="gap: 16px; margin-bottom: 28px;">
        <div>
            <p style="margin: 0 0 6px; color: var(--gr-muted);">Welcome back</p>
            <h1 style="margin: 0; font-size: clamp(32px, 4vw, 46px);">{{ $grDealer->company_name ?: $grDealer->name }}</h1>
        </div>
        <a href="{{ route('frontend.shop') }}" class="gr-btn gr-btn-primary">Start a new order</a>
    </div>

    {{-- Order counts by status (same data as before) --}}
    <div class="row g-3" style="margin-bottom: 32px;">
        @forelse ($ordersCountByStatus as $status => $count)
            <div class="col-sm-6 col-lg-3">
                <a href="{{ route('dealer.orders') }}" class="gr-stat-card h-100">
                    <p style="margin: 0 0 10px; display: flex; gap: 8px; align-items: center; font-size: 14px; color: var(--gr-muted); text-transform: capitalize;">
                        <span class="gr-dot" style="background: {{ $grStatusColors[$status] ?? '#9A9184' }};"></span>{{ $status }}
                    </p>
                    <p style="margin: 0; font-family: var(--gr-font-display); font-size: 40px; line-height: 1;">{{ $count }}</p>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="gr-card" style="padding: 28px; text-align: center;">
                    <p style="font-family: var(--gr-font-display); font-size: 24px; margin: 0 0 8px;">No orders yet</p>
                    <p style="color: var(--gr-muted); margin: 0 0 16px;">Your orders will show up here once you place one.</p>
                    <a href="{{ route('frontend.shop') }}" class="gr-btn gr-btn-outline gr-btn-sm">Browse the catalog</a>
                </div>
            </div>
        @endforelse
    </div>

    <div class="row g-4">
        {{-- Recent orders --}}
        <div class="col-lg-7">
            <div class="gr-card h-100" style="padding: 24px 26px;">
                <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 8px;">
                    <h2 style="font-family: var(--gr-font-body); font-size: 18px; font-weight: 600; margin: 0;">Recent orders</h2>
                    <a href="{{ route('dealer.orders') }}" class="gr-link" style="font-size: 14px;">All orders →</a>
                </div>
                @forelse ($grRecentOrders as $order)
                    <div class="d-flex flex-wrap align-items-center" style="gap: 12px; padding: 14px 0; border-top: 1px solid var(--gr-line-soft);">
                        <div style="flex: 1 1 160px; min-width: 0;">
                            <p style="margin: 0; font-weight: 600;">Order #{{ $order->id }}
                                <span style="font-weight: 400; color: var(--gr-muted);">· {{ $order->order_items_count }} {{ $order->order_items_count == 1 ? 'item' : 'items' }}</span>
                            </p>
                            <p style="margin: 2px 0 0; font-size: 13px; color: var(--gr-muted);">{{ $order->created_at?->format('M j, Y') }}@if ($order->po_number) · {{ $order->po_number }}@endif</p>
                        </div>
                        <span class="gr-status gr-status-{{ $order->status }}">{{ $order->status }}</span>
                        <span style="min-width: 90px; text-align: right; font-weight: 600;">${{ number_format((float) $order->total, 2) }}</span>
                    </div>
                @empty
                    <p style="color: var(--gr-muted); margin: 12px 0 0;">No orders yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Side cards --}}
        <div class="col-lg-5 d-flex flex-column" style="gap: 20px;">
            <div style="background: var(--gr-madder); color: #fff; border-radius: 18px; padding: 24px;">
                <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 10px;">
                    <h2 style="font-family: var(--gr-font-body); font-size: 17px; font-weight: 600; margin: 0; color: #fff;">Customer Mode</h2>
                    <span style="padding: 4px 10px; border-radius: 999px; background: rgba(255,255,255,.18); font-size: 12px; font-weight: 600;">{{ $grDealer->is_customer_mode_active ? 'On' : 'Off' }}</span>
                </div>
                <p style="margin: 0 0 16px; font-size: 14px; line-height: 1.55; color: #F1DCD5;">Show the catalog to shoppers in your store with your retail prices and your store's banner.</p>
                <a href="{{ route('dealer.customerMode') }}" class="gr-btn gr-btn-sm" style="background: #fff; color: var(--gr-madder);">Customer Mode settings</a>
            </div>
            <div class="gr-card" style="padding: 24px;">
                <h2 style="font-family: var(--gr-font-body); font-size: 17px; font-weight: 600; margin: 0 0 6px;">Need help with an order?</h2>
                <p style="margin: 0 0 12px; font-size: 14px; color: var(--gr-muted);">Our team picks up 7 days a week.</p>
                <a href="tel:+17734903801" style="font-family: var(--gr-font-display); font-size: 24px; color: var(--gr-ink); text-decoration: none;">(773) 490-3801</a>
            </div>
        </div>
    </div>
</div>
