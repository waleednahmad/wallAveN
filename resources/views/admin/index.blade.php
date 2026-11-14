@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => ' Welcome, ' . auth()->user()->name,
        'links' => [
            [
                'name' => 'Dashboard',
                'url' => route('dashboard'),
            ],
        ],
    ])
@endsection

@php
    $money = fn ($n) => '$' . number_format((float) $n, 0);
    $statusBadge = [
        'pending' => 'warning',
        'processing' => 'info',
        'completed' => 'success',
        'declined' => 'danger',
        'canceled' => 'secondary',
    ];
    $vsLastMonth = $salesLastMonthToDate > 0
        ? round((($salesThisMonth - $salesLastMonthToDate) / $salesLastMonthToDate) * 100)
        : null;
    $dealerName = fn ($d) => $d?->company_name ?: ($d?->name ?: 'Unknown dealer');
@endphp

@section('content')
    <style>
        .gr-kpi { display: block; height: 100%; padding: 18px 20px; border-radius: 12px; background: #fff; border: 1px solid #e7e2da; color: #231A14; text-decoration: none; transition: box-shadow .2s ease, transform .2s ease; }
        a.gr-kpi:hover { color: #231A14; text-decoration: none; box-shadow: 0 10px 24px rgba(35, 26, 20, .08); transform: translateY(-1px); }
        .gr-kpi-label { font-size: 13px; font-weight: 600; letter-spacing: .04em; text-transform: uppercase; color: #6b5f55; margin: 0 0 8px; display: flex; align-items: center; gap: 8px; }
        .gr-kpi-value { font-size: 30px; font-weight: 700; line-height: 1.1; margin: 0; font-variant-numeric: tabular-nums; }
        .gr-kpi-sub { font-size: 13px; color: #6b5f55; margin: 6px 0 0; }
        .gr-kpi.is-alert { border-color: #e9c98b; background: #fffaf0; }
        .gr-kpi.is-alert .gr-kpi-value { color: #7B2D26; }
        .gr-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
        .gr-panel { border-radius: 12px; border: 1px solid #e7e2da; box-shadow: none; }
        .gr-panel .card-header { background: transparent; border-bottom: 1px solid #efebe5; }
        .gr-panel .card-title { font-weight: 600; font-size: 16px; }
        .gr-panel table td, .gr-panel table th { vertical-align: middle; }
        .gr-num { font-variant-numeric: tabular-nums; white-space: nowrap; }
        .gr-up { color: #2f7a4a; } .gr-down { color: #a3392e; }
        .gr-bar { height: 8px; border-radius: 4px; background: #efebe5; overflow: hidden; }
        .gr-bar > span { display: block; height: 100%; border-radius: 4px; }
    </style>

    {{-- ============= Needs attention / KPIs ============= --}}
    <div class="row">
        <div class="col-lg-3 col-sm-6 mb-3">
            <a href="{{ route('dashboard.orders.index') }}" @class(['gr-kpi', 'is-alert' => $pendingOrders > 0])>
                <p class="gr-kpi-label"><i class="fas fa-inbox"></i> Pending orders</p>
                <p class="gr-kpi-value">{{ number_format($pendingOrders) }}</p>
                <p class="gr-kpi-sub">{{ $money($pendingOrdersAmount) }} waiting to be processed</p>
            </a>
        </div>
        <div class="col-lg-3 col-sm-6 mb-3">
            <a href="{{ route('dashboard.dealers.index') }}" @class(['gr-kpi', 'is-alert' => $pendingDealersCount > 0])>
                <p class="gr-kpi-label"><i class="fas fa-user-clock"></i> Dealer applications</p>
                <p class="gr-kpi-value">{{ number_format($pendingDealersCount) }}</p>
                <p class="gr-kpi-sub">waiting for approval · {{ number_format($approvedDealers) }} approved dealers</p>
            </a>
        </div>
        <div class="col-lg-3 col-sm-6 mb-3">
            <a href="{{ route('dashboard.orders.index') }}" class="gr-kpi">
                <p class="gr-kpi-label"><i class="fas fa-chart-line"></i> Sales in {{ $monthLabel }}</p>
                <p class="gr-kpi-value">{{ $money($salesThisMonth) }}</p>
                <p class="gr-kpi-sub">
                    {{ number_format($ordersThisMonth) }} {{ $ordersThisMonth === 1 ? 'order' : 'orders' }}
                    @if (!is_null($vsLastMonth))
                        · <span @class(['gr-up' => $vsLastMonth >= 0, 'gr-down' => $vsLastMonth < 0])>{{ $vsLastMonth >= 0 ? '▲' : '▼' }} {{ abs($vsLastMonth) }}%</span> vs same days last month
                    @endif
                </p>
            </a>
        </div>
        <div class="col-lg-3 col-sm-6 mb-3">
            <a href="{{ route('dashboard.abandoned-orders.index') }}" class="gr-kpi">
                <p class="gr-kpi-label"><i class="fas fa-shopping-cart"></i> Open dealer carts</p>
                <p class="gr-kpi-value">{{ number_format($openCartsCount) }}</p>
                <p class="gr-kpi-sub">{{ $money($openCartsAmount) }} in carts not checked out</p>
            </a>
        </div>
    </div>

    <div class="row">
        {{-- ============= Recent orders ============= --}}
        <div class="col-lg-8 mb-3">
            <div class="card gr-panel h-100 mb-0">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title mb-0">Latest orders</h3>
                    <a href="{{ route('dashboard.orders.index') }}" class="ml-auto small">All orders →</a>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Dealer</th>
                                <th>Status</th>
                                <th class="text-right">Items</th>
                                <th class="text-right">Total</th>
                                <th class="text-right">Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentOrders as $order)
                                <tr>
                                    <td class="gr-num">{{ $order->id }}</td>
                                    <td>{{ $dealerName($order->dealer) }}</td>
                                    <td><span class="badge badge-{{ $statusBadge[$order->status] ?? 'secondary' }}">{{ ucfirst($order->status) }}</span></td>
                                    <td class="text-right gr-num">{{ number_format($order->quantity) }}</td>
                                    <td class="text-right gr-num">${{ number_format($order->total, 2) }}</td>
                                    <td class="text-right gr-num" title="{{ $order->created_at }}">{{ $order->created_at?->diffForHumans() }}</td>
                                    <td class="text-right"><a href="{{ route('dashboard.orders.print', $order->id) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">View</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">No orders yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ============= Dealer applications waiting ============= --}}
        <div class="col-lg-4 mb-3">
            <div class="card gr-panel h-100 mb-0">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title mb-0">Applications waiting</h3>
                    <a href="{{ route('dashboard.dealers.index') }}" class="ml-auto small">All dealers →</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse ($pendingDealers as $dealer)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $dealerName($dealer) }}</strong>
                                    <small class="text-muted" title="{{ $dealer->created_at }}">{{ $dealer->created_at?->diffForHumans() }}</small>
                                </div>
                                <small class="text-muted">
                                    {{ $dealer->email }}@if ($dealer->city || $dealer->state) · {{ trim($dealer->city . ', ' . $dealer->state, ', ') }}@endif
                                </small>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">No applications waiting. 👍</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- ============= Top dealers this month ============= --}}
        <div class="col-lg-6 mb-3">
            <div class="card gr-panel h-100 mb-0">
                <div class="card-header"><h3 class="card-title mb-0">Top dealers in {{ $monthLabel }}</h3></div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <tbody>
                            @forelse ($topDealers as $row)
                                <tr>
                                    <td>{{ $dealerName($row->dealer) }}</td>
                                    <td class="text-right text-muted gr-num">{{ $row->orders }} {{ $row->orders == 1 ? 'order' : 'orders' }}</td>
                                    <td class="text-right gr-num"><strong>${{ number_format($row->amount, 2) }}</strong></td>
                                </tr>
                            @empty
                                <tr><td class="text-center text-muted py-4">No orders this month yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ============= Orders by status (all time) ============= --}}
        <div class="col-lg-6 mb-3">
            <div class="card gr-panel h-100 mb-0">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title mb-0">Orders by status</h3>
                    <span class="ml-auto small text-muted">all time · {{ number_format($activeProducts) }} active products</span>
                </div>
                <div class="card-body">
                    @php $allOrders = max(1, $statusCounts->sum('n')); @endphp
                    @foreach (['pending', 'processing', 'completed', 'declined', 'canceled'] as $status)
                        @php $n = (int) ($statusCounts[$status]->n ?? 0); @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span><span class="gr-dot bg-{{ $statusBadge[$status] }}"></span> {{ ucfirst($status) }}</span>
                                <span class="gr-num">{{ number_format($n) }} · ${{ number_format((float) ($statusCounts[$status]->amount ?? 0), 0) }}</span>
                            </div>
                            <div class="gr-bar"><span class="bg-{{ $statusBadge[$status] }}" style="width: {{ round($n / $allOrders * 100, 1) }}%"></span></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
