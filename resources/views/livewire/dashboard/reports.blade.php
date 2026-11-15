<div class="adm-reports">
    @php $money = fn ($n, $d = 0) => '$' . number_format((float) $n, $d); @endphp

    {{-- Period picker --}}
    <div class="adm-rep-bar">
        <div class="adm-seg" role="group" aria-label="Period">
            @foreach ($ranges as $key => $label)
                <button type="button" @class(['adm-seg-btn', 'is-on' => $range === $key]) wire:click="setRange('{{ $key }}')">{{ $label }}</button>
            @endforeach
        </div>
        @if ($range === 'custom')
            <div class="adm-rep-custom">
                <label class="sr-only" for="rep-from">From</label>
                <input id="rep-from" type="date" class="form-control form-control-sm" wire:model.live="from">
                <span class="text-muted">to</span>
                <label class="sr-only" for="rep-to">To</label>
                <input id="rep-to" type="date" class="form-control form-control-sm" wire:model.live="to">
            </div>
        @endif
        <div class="adm-rep-right">
            <span class="adm-rep-period">{{ $periodLabel }}</span>
            <button type="button" class="btn btn-secondary btn-sm" wire:click="exportCsv" wire:loading.attr="disabled" wire:target="exportCsv">
                <i class="fas fa-download" aria-hidden="true"></i> Export orders (CSV)
            </button>
        </div>
    </div>

    <div wire:loading.class="adm-rep-busy" wire:target="setRange,from,to">
        {{-- KPIs --}}
        <div class="adm-rep-kpis">
            @php
                $trend = function ($pct, $label) {
                    if ($pct === null) return '<p class="adm-kpi-sub">no orders in ' . e($label) . '</p>';
                    $up = $pct >= 0;
                    return '<p class="adm-kpi-sub"><span class="adm-trend ' . ($up ? 'is-up' : 'is-down') . '"><i class="fas fa-arrow-' . ($up ? 'up' : 'down') . '" aria-hidden="true"></i> ' . abs($pct) . '%</span> vs ' . e($label) . '</p>';
                };
            @endphp
            <div class="adm-rep-kpi"><p class="adm-kpi-label">Sales</p><p class="adm-kpi-value">{{ $money($kpi->sales) }}</p>@if ($previous){!! $trend($salesChange, $previous->label) !!}@else<p class="adm-kpi-sub">not counting canceled or declined</p>@endif</div>
            <div class="adm-rep-kpi"><p class="adm-kpi-label">Completed</p><p class="adm-kpi-value">{{ $money($completed) }}</p><p class="adm-kpi-sub">{{ $kpi->sales > 0 ? round($completed / $kpi->sales * 100) : 0 }}% of sales marked completed</p></div>
            <div class="adm-rep-kpi"><p class="adm-kpi-label">Orders</p><p class="adm-kpi-value">{{ number_format($kpi->orders) }}</p>@if ($previous){!! $trend($ordersChange, $previous->label) !!}@else<p class="adm-kpi-sub">{{ number_format($kpi->items) }} items</p>@endif</div>
            <div class="adm-rep-kpi"><p class="adm-kpi-label">Average order</p><p class="adm-kpi-value">{{ $money($avgOrder) }}</p><p class="adm-kpi-sub">per order</p></div>
            <div class="adm-rep-kpi"><p class="adm-kpi-label">Ordering dealers</p><p class="adm-kpi-value">{{ number_format($kpi->dealers) }}</p><p class="adm-kpi-sub">{{ number_format($newDealers) }} newly approved</p></div>
        </div>

        {{-- Monthly trend --}}
        <section class="card">
            <div class="card-header d-flex align-items-center"><h3 class="card-title mb-0">Sales by month</h3><span class="ml-auto small text-muted">last 12 months</span></div>
            <div class="card-body">
                <div class="adm-bars" role="img" aria-label="Monthly sales for the last 12 months">
                    @foreach ($months as $m)
                        <div class="adm-bar-col" title="{{ $m['label'] }} {{ $m['year'] }}: {{ $money($m['sales'], 2) }}">
                            <span class="adm-bar-val">{{ $m['sales'] > 0 ? ($m['sales'] >= 1000 ? '$' . round($m['sales'] / 1000, $m['sales'] >= 10000 ? 0 : 1) . 'k' : $money($m['sales'])) : '' }}</span>
                            <span class="adm-bar-fill" style="height: {{ max(2, round($m['sales'] / $maxMonth * 100)) }}%"></span>
                            <span class="adm-bar-lbl">{{ $m['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <div class="row">
            @php
                // Clickable header: shows which column the list is ranked by and in which direction
                $th = function (string $label, string $col, string $current, string $dir, string $method, string $align = '') {
                    $on = $col === $current;
                    $icon = $on ? ($dir === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort';
                    $aria = $on ? ($dir === 'asc' ? 'ascending' : 'descending') : 'none';
                    return '<th class="' . $align . '" aria-sort="' . $aria . '"><button type="button" class="adm-sort' . ($on ? ' is-on' : '') . '" wire:click="' . $method . '(\'' . $col . '\')">'
                        . e($label) . ' <i class="fas ' . $icon . '" aria-hidden="true"></i></button></th>';
                };
                $periodSales = max(0.01, (float) $kpi->sales);
            @endphp

            {{-- How many rows the two ranked lists show --}}
            <div class="col-12 mb-2 d-flex align-items-center justify-content-end gap-2">
                <span class="small text-muted mr-2">Show</span>
                <div class="adm-seg adm-seg-sm" role="group" aria-label="Rows in top lists">
                    @foreach ([10, 25, 50] as $n)
                        <button type="button" @class(['adm-seg-btn', 'is-on' => $topLimit === $n]) wire:click="setTopLimit({{ $n }})">Top {{ $n }}</button>
                    @endforeach
                </div>
            </div>

            {{-- Top dealers (full width: six columns don't fit half a screen) --}}
            <div class="col-12 mb-3">
                <section class="card h-100 mb-0">
                    <div class="card-header"><h3 class="card-title mb-0">Top dealers</h3></div>
                    <div class="card-body p-0 table-responsive" wire:loading.class="adm-rep-busy" wire:target="sortDealers,setTopLimit">
                        <table class="table mb-0 adm-rank-table">
                            <thead>
                                <tr>
                                    <th class="adm-rank" scope="col">#</th>
                                    {!! $th('Dealer', 'name', $dealerSort, $dealerDir, 'sortDealers') !!}
                                    {!! $th('Orders', 'orders', $dealerSort, $dealerDir, 'sortDealers', 'text-right') !!}
                                    {!! $th('Sales', 'sales', $dealerSort, $dealerDir, 'sortDealers', 'text-right') !!}
                                    {!! $th('Avg. order', 'avg', $dealerSort, $dealerDir, 'sortDealers', 'text-right') !!}
                                    {!! $th('Last order', 'last', $dealerSort, $dealerDir, 'sortDealers', 'text-right') !!}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topDealers as $row)
                                    <tr>
                                        <td class="adm-rank">{{ $loop->iteration }}</td>
                                        <td><a href="{{ route('dashboard.dealers.index', ['dealer' => $row->dealer_id]) }}">{{ $row->name }}</a></td>
                                        <td class="text-right adm-num">{{ number_format($row->orders) }}</td>
                                        <td class="text-right adm-num">
                                            <strong>{{ $money($row->sales, 2) }}</strong>
                                            <div class="adm-share" title="{{ round($row->sales / $periodSales * 100, 1) }}% of sales in this period"><span style="width: {{ min(100, round($row->sales / $periodSales * 100, 1)) }}%"></span></div>
                                        </td>
                                        <td class="text-right adm-num">{{ $money($row->avg_order) }}</td>
                                        <td class="text-right adm-num text-muted">{{ \Illuminate\Support\Carbon::parse($row->last_order)->format('M j, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted py-4">No orders in this period.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            {{-- Top products (full width: six columns don't fit half a screen) --}}
            <div class="col-12 mb-3">
                <section class="card h-100 mb-0">
                    <div class="card-header"><h3 class="card-title mb-0">Top products</h3></div>
                    <div class="card-body p-0 table-responsive" wire:loading.class="adm-rep-busy" wire:target="sortProducts,setTopLimit">
                        <table class="table mb-0 adm-rank-table">
                            <thead>
                                <tr>
                                    <th class="adm-rank" scope="col">#</th>
                                    {!! $th('Product', 'name', $productSort, $productDir, 'sortProducts') !!}
                                    {!! $th('Units', 'units', $productSort, $productDir, 'sortProducts', 'text-right') !!}
                                    {!! $th('Orders', 'orders', $productSort, $productDir, 'sortProducts', 'text-right') !!}
                                    {!! $th('Sales', 'sales', $productSort, $productDir, 'sortProducts', 'text-right') !!}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topProducts as $row)
                                    @php $img = $row->image && file_exists($row->image) ? thumb($row->image, 240) : asset('dashboard/images/default.webp'); @endphp
                                    <tr>
                                        <td class="adm-rank">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="adm-rank-product">
                                                <img src="{{ $img }}" alt="" loading="lazy">
                                                <span>
                                                    @if ($row->product_id && !$row->deleted_at)
                                                        <a href="{{ route('dashboard.products.edit', $row->product_id) }}">{{ $row->name }}</a>
                                                    @else
                                                        {{ $row->name }} <span class="badge badge-secondary">Removed</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-right adm-num">{{ number_format($row->qty) }}</td>
                                        <td class="text-right adm-num">{{ number_format($row->orders) }}</td>
                                        <td class="text-right adm-num">
                                            <strong>{{ $money($row->sales, 2) }}</strong>
                                            <div class="adm-share" title="{{ round($row->sales / $periodSales * 100, 1) }}% of sales in this period"><span style="width: {{ min(100, round($row->sales / $periodSales * 100, 1)) }}%"></span></div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">No items sold in this period.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            {{-- Where orders come from --}}
            <div class="col-xl-4 mb-3">
                <section class="card h-100 mb-0">
                    <div class="card-header"><h3 class="card-title mb-0">Who places the orders</h3></div>
                    <div class="card-body">
                        @php $chTotal = max(1, $channels->sum('sales')); @endphp
                        @forelse ($channels as $ch)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small mb-1"><span>{{ $ch->channel }}</span><span class="adm-num">{{ $ch->orders }} · {{ $money($ch->sales) }}</span></div>
                                <div class="adm-bar"><span class="adm-tone-processing" style="width: {{ round($ch->sales / $chTotal * 100, 1) }}%"></span></div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No orders in this period.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            {{-- Sales reps --}}
            <div class="col-xl-4 mb-3">
                <section class="card h-100 mb-0">
                    <div class="card-header"><h3 class="card-title mb-0">Sales reps</h3></div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table mb-0">
                            <thead><tr><th>Rep</th><th class="text-right">Dealers</th><th class="text-right">Sales</th></tr></thead>
                            <tbody>
                                @forelse ($reps as $rep)
                                    <tr><td>{{ $rep->name }}</td><td class="text-right adm-num">{{ $rep->dealers }}</td><td class="text-right adm-num"><strong>{{ $money($rep->sales) }}</strong> <span class="text-muted small">({{ $rep->orders }})</span></td></tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted py-4">No orders placed by sales reps in this period.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            {{-- Status mix for the period --}}
            <div class="col-xl-4 mb-3">
                <section class="card h-100 mb-0">
                    <div class="card-header"><h3 class="card-title mb-0">Orders by status</h3><span class="ml-auto small text-muted">this period</span></div>
                    <div class="card-body">
                        @php $smTotal = max(1, $statusMix->sum('n')); @endphp
                        @foreach (['pending', 'processing', 'completed', 'declined', 'canceled'] as $st)
                            @php $n = (int) ($statusMix[$st]->n ?? 0); @endphp
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small mb-1"><span><span class="adm-dot adm-tone-{{ $st }}"></span>{{ ucfirst($st) }}</span><span class="adm-num">{{ $n }} · {{ $money($statusMix[$st]->amount ?? 0) }}</span></div>
                                <div class="adm-bar"><span class="adm-tone-{{ $st }}" style="width: {{ round($n / $smTotal * 100, 1) }}%"></span></div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
