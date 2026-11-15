<div class="adm-drawer">
    @php $order = $this->order; @endphp
    @if (!$order)
        <div class="adm-drawer-empty">
            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Loading order…
        </div>
    @else
        @php
            $dealer = $order->dealer;
            $steps = ['pending' => 'Pending', 'processing' => 'Processing', 'completed' => 'Completed'];
            $stepIndex = array_search($order->status, array_keys($steps), true);
            $next = ['pending' => ['processing', 'Mark as processing'], 'processing' => ['completed', 'Mark as completed']][$order->status] ?? null;
            $canDecline = in_array($order->status, ['pending', 'processing'], true);
            $isClosed = in_array($order->status, ['declined', 'canceled'], true);
            $placedBy = $order->representative_id ? 'Sales rep' : ($order->admin_id ? 'Admin' : 'Dealer');
            $billTo = array_filter([
                $dealer?->address ? trim(explode(',', $dealer->address)[0]) : null,
                trim(implode(', ', array_filter([$dealer?->city, trim(($dealer?->state ?? '') . ' ' . ($dealer?->zip_code ?? ''))]))),
            ]);
            $shipTo = $order->shipping_address ?: implode("\n", $billTo);
            $money = fn ($n) => '$' . number_format((float) $n, 2);
            $itemCount = (int) $order->orderItems->sum('quantity');
        @endphp

        {{-- Header --}}
        <div class="adm-drawer-head">
            <p class="adm-drawer-meta">Order #{{ $order->id }} · {{ $order->created_at?->format('M j, Y · g:i A') }}</p>
            <h2 class="adm-drawer-title">{{ $dealer?->company_name ?: ($dealer?->name ?: 'Unknown dealer') }}</h2>
        </div>

        {{-- Status --}}
        <section class="adm-drawer-sec">
            <p class="adm-drawer-label">Status</p>
            @if ($isClosed)
                <div class="adm-closed adm-closed-{{ $order->status }}">
                    {{ $order->status === 'canceled' ? 'Canceled by the dealer' : 'Declined' }}
                </div>
            @else
                <ol class="adm-steps" aria-label="Order progress">
                    @foreach ($steps as $key => $label)
                        @php
                            $i = $loop->index;
                            // A completed order shows every step ticked, including the last one
                            $isDone = $stepIndex !== false && ($i < $stepIndex || $order->status === 'completed');
                        @endphp
                        <li @class(['adm-step', 'is-done' => $isDone, 'is-on' => $i === $stepIndex])>
                            <span class="adm-step-dot" aria-hidden="true">
                                @if ($isDone)
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12.5l4.2 4.2L19 7"/></svg>
                                @else
                                    {{ $i + 1 }}
                                @endif
                            </span>
                            {{ $label }}
                        </li>
                    @endforeach
                </ol>
            @endif

            @if ($order->status !== 'canceled')
                <div class="adm-drawer-actions">
                    @if ($next)
                        <button type="button" class="btn btn-primary flex-fill" wire:click="setStatus('{{ $next[0] }}')" wire:loading.attr="disabled" wire:target="setStatus">
                            <span wire:loading wire:target="setStatus" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                            {{ $next[1] }}
                        </button>
                    @endif
                    @if ($canDecline)
                        <button type="button" class="btn btn-secondary" wire:click="setStatus('declined')" wire:confirm="Decline order #{{ $order->id }}?" wire:loading.attr="disabled" wire:target="setStatus">Decline</button>
                    @endif
                </div>
                <div class="adm-drawer-row">
                    <label class="adm-check">
                        <input type="checkbox" wire:model="notifyDealer"> Email the dealer about the change
                    </label>
                    <div class="dropdown ms-auto">
                        <button class="btn btn-sm btn-link dropdown-toggle p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">Set another status</button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @foreach (['pending' => 'Pending', 'processing' => 'Processing', 'completed' => 'Completed', 'declined' => 'Declined'] as $key => $label)
                                @if ($key !== $order->status)
                                    <li><button type="button" class="dropdown-item" wire:click="setStatus('{{ $key }}')">{{ $label }}</button></li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </section>

        {{-- Dealer + shipping --}}
        <section class="adm-drawer-sec">
            <p class="adm-drawer-label">Dealer</p>
            <dl class="adm-dl">
                @if ($dealer?->email)<div><dt>Email</dt><dd><a href="mailto:{{ $dealer->email }}">{{ $dealer->email }}</a></dd></div>@endif
                @if ($dealer?->phone)<div><dt>Phone</dt><dd><a href="tel:{{ $dealer->phone }}">{{ $dealer->phone }}</a></dd></div>@endif
                @if ($order->po_number)<div><dt>PO number</dt><dd>{{ $order->po_number }}</dd></div>@endif
                <div><dt>Placed by</dt><dd>{{ $placedBy }}</dd></div>
                @if ($billTo)<div><dt>Bill to</dt><dd class="adm-pre">{{ implode("\n", $billTo) }}</dd></div>@endif
                @if ($shipTo)<div><dt>Ship to</dt><dd class="adm-pre">{{ $shipTo }}</dd></div>@endif
            </dl>
        </section>

        {{-- Items --}}
        <section class="adm-drawer-sec adm-drawer-grow">
            <p class="adm-drawer-label">Items ({{ $itemCount }})</p>
            <ul class="adm-items">
                @foreach ($order->orderItems as $item)
                    @php
                        $attrs = json_decode($item->attributes ?? '[]', true) ?: [];
                        $attrs = array_filter($attrs, fn ($v) => strtolower((string) $v) !== 'none');
                        $attrText = str_replace('"', "'", implode(' · ', array_map('ucwords', array_values($attrs))));
                    @endphp
                    <li class="adm-item">
                        <img src="{{ $item->image ? thumb($item->image, 240) : asset('dashboard/images/default.webp') }}" alt="" loading="lazy">
                        <div class="adm-item-body">
                            <div class="adm-item-name">{{ $item->name }}</div>
                            <div class="adm-item-sub">{{ $attrText }}{{ $attrText && $item->sku ? ' · ' : '' }}{{ $item->sku ? 'SKU ' . $item->sku : '' }}</div>
                        </div>
                        <div class="adm-item-price">
                            <div>{{ $money($item->total) }}</div>
                            <div class="adm-item-sub">{{ $item->quantity }} × {{ $money($item->price) }}</div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </section>

        {{-- Totals + documents --}}
        <section class="adm-drawer-foot">
            <div class="d-flex justify-content-between align-items-baseline">
                <span class="fw-semibold">Order total</span>
                <span class="adm-drawer-total">{{ $money($order->total) }}</span>
            </div>
            <div class="adm-drawer-actions mt-3">
                <a class="btn btn-secondary flex-fill" href="{{ route('dashboard.orders.print', $order->id) }}" target="_blank" rel="noopener"><i class="fas fa-print" aria-hidden="true"></i> Print invoice</a>
                <a class="btn btn-secondary flex-fill" href="{{ route('dashboard.orders.pdf', $order->id) }}"><i class="fas fa-file-pdf" aria-hidden="true"></i> Download PDF</a>
            </div>
        </section>
    @endif
</div>
