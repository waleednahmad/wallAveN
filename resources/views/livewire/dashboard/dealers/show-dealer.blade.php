<div class="adm-profile">
    @if (!$dealer)
        <p class="text-muted"><span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Loading dealer…</p>
    @else
        @php
            $money = fn ($n, $d = 0) => '$' . number_format((float) $n, $d);
            $statusTone = ['pending' => 'warning', 'processing' => 'info', 'completed' => 'success', 'declined' => 'danger', 'canceled' => 'secondary'];
            $website = $dealer->website ? (parse_url($dealer->website, PHP_URL_SCHEME) ? $dealer->website : 'https://' . $dealer->website) : null;
            $state = $dealer->is_approved ? ['Approved', 'success'] : ($dealer->rejected_at ? ['Declined', 'danger'] : ['Application waiting', 'warning']);
        @endphp

        {{-- Header --}}
        <div class="adm-profile-head">
            <h3 class="adm-profile-name">{{ $dealer->company_name ?: ($dealer->name ?: '—') }}</h3>
            <p class="adm-profile-sub">{{ $dealer->name }}{{ $dealer->name && ($dealer->city || $dealer->state) ? ' · ' : '' }}{{ trim(implode(', ', array_filter([$dealer->city, $dealer->state]))) }}</p>
            <div class="adm-profile-tags">
                <span class="badge badge-{{ $state[1] }}">{{ $state[0] }}</span>
                <span class="badge badge-{{ $dealer->status ? 'success' : 'secondary' }}">{{ $dealer->status ? 'Active' : 'Inactive' }}</span>
                @if ($dealer->priceList)<span class="badge badge-primary">Price list: {{ $dealer->priceList->name }}</span>@endif
            </div>
        </div>

        {{-- Buying summary --}}
        @if ($stats)
            <div class="adm-profile-stats">
                <div><p class="adm-kpi-label">Lifetime sales</p><p class="adm-profile-num">{{ $money($stats->sales) }}</p></div>
                <div><p class="adm-kpi-label">Orders</p><p class="adm-profile-num">{{ number_format($stats->orders) }}</p></div>
                <div><p class="adm-kpi-label">Average order</p><p class="adm-profile-num">{{ $stats->orders ? $money($stats->sales / $stats->orders) : '—' }}</p></div>
                <div><p class="adm-kpi-label">Last order</p><p class="adm-profile-num adm-profile-num-sm">{{ $stats->last_order ? \Illuminate\Support\Carbon::parse($stats->last_order)->diffForHumans() : 'Never' }}</p></div>
            </div>
        @endif

        @if ($cart && $cart->items > 0)
            <a class="adm-profile-cart" href="{{ route('dashboard.abandoned-orders.index') }}">
                <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                <span><strong>{{ $money($cart->amount, 2) }}</strong> in an open cart ({{ $cart->items }} items) · last added {{ \Illuminate\Support\Carbon::parse($cart->last_activity)->diffForHumans() }}</span>
            </a>
        @endif

        @if ($dealer->rejected_at)
            <div class="alert alert-danger mb-0">
                <strong>Declined {{ \Illuminate\Support\Carbon::parse($dealer->rejected_at)->format('M j, Y') }}.</strong>
                {{ $dealer->rejection_reason ?: 'No reason recorded.' }}
            </div>
        @endif

        {{-- Recent orders --}}
        <section>
            <p class="adm-drawer-label">Recent orders</p>
            @if ($recentOrders && $recentOrders->isNotEmpty())
                <ul class="adm-profile-orders">
                    @foreach ($recentOrders as $order)
                        <li>
                            <a href="{{ route('dashboard.orders.index', ['order' => $order->id]) }}">#{{ $order->id }}</a>
                            <span class="text-muted">{{ $order->created_at?->format('M j, Y') }}</span>
                            <span class="badge badge-{{ $statusTone[$order->status] ?? 'secondary' }}">{{ ucfirst($order->status) }}</span>
                            <span class="adm-num ml-auto">{{ $money($order->total, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted small mb-0">No orders yet.</p>
            @endif
        </section>

        {{-- Details --}}
        <section>
            <p class="adm-drawer-label">Contact &amp; business</p>
            <dl class="adm-dl">
                <div><dt>Email</dt><dd>@if ($dealer->email)<a href="mailto:{{ $dealer->email }}">{{ $dealer->email }}</a>@else — @endif</dd></div>
                <div><dt>Phone</dt><dd>@if ($dealer->phone)<a href="tel:{{ $dealer->phone }}">{{ $dealer->phone }}</a>@else — @endif</dd></div>
                <div><dt>Address</dt><dd class="adm-pre">{{ trim(implode("\n", array_filter([$dealer->address, trim(implode(', ', array_filter([$dealer->city, trim(($dealer->state ?? '') . ' ' . ($dealer->zip_code ?? ''))])))]))) ?: '—' }}</dd></div>
                <div><dt>Tax ID</dt><dd>{{ $dealer->tax_id ?: '—' }}</dd></div>
                <div><dt>Business type</dt><dd>{{ $dealer->business_type ?: '—' }}</dd></div>
                <div><dt>Years in business</dt><dd>{{ $dealer->years_in_business ?: '—' }}</dd></div>
                <div><dt>Website</dt><dd>@if ($website)<a href="{{ $website }}" target="_blank" rel="noopener nofollow">{{ strtolower(parse_url($website, PHP_URL_HOST) . parse_url($website, PHP_URL_PATH)) }}</a>@else — @endif</dd></div>
                <div><dt>Resale cert.</dt><dd>@if ($dealer->resale_certificate && file_exists(public_path($dealer->resale_certificate)))<a href="{{ asset($dealer->resale_certificate) }}" target="_blank" rel="noopener">Open document</a>@else Not uploaded @endif</dd></div>
                <div><dt>Referred by</dt><dd>{{ $dealer->referalTo?->name ?: '—' }}</dd></div>
                <div><dt>Applied</dt><dd>{{ $dealer->created_at?->format('M j, Y') ?: '—' }}</dd></div>
                <div><dt>Approved</dt><dd>{{ $dealer->approved_at ? \Illuminate\Support\Carbon::parse($dealer->approved_at)->format('M j, Y') : '—' }}</dd></div>
            </dl>
        </section>

        @if ($dealer->message)
            <section>
                <p class="adm-drawer-label">Message from the application</p>
                <p class="adm-app-msg">“{{ $dealer->message }}”</p>
            </section>
        @endif
    @endif
</div>
