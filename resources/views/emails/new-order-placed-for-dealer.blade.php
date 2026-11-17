@extends('emails.layout')
@php
    // One email, three readers: the dealer, their sales rep and the admins
    $audience = $audience ?? 'dealer';
    $dealer = $order->dealer;
    $dealerName = $dealer?->company_name ?: ($dealer?->name ?: 'a dealer');
    $order->loadMissing('orderItems.variant', 'orderItems.product');
    $items = $order->orderItems;
    $addressLines = array_values(array_filter([
        $dealer?->address,
        trim(implode(', ', array_filter([$dealer?->city, trim(($dealer?->state ?? '') . ' ' . ($dealer?->zip_code ?? ''))]))),
    ]));
    $shown = $items->take(10);
    $more = $items->count() - $shown->count();
    $money = fn ($n) => '$' . number_format((float) $n, 2);
    $placedBy = $order->representative_id ? 'Sales rep' : ($order->admin_id ? 'Our team' : 'Online by the dealer');
    $specs = function ($item) {
        $attrs = is_array($item->attributes) ? $item->attributes : (json_decode((string) $item->attributes, true) ?: []);
        $parts = [];
        foreach ($attrs as $k => $v) {
            if ($v === null || $v === '' || strtolower((string) $v) === 'none' || strtolower((string) $k) === 'none') continue;
            $parts[] = ucwords((string) $k) . ' ' . str_replace('"', "'", (string) $v);
        }
        return implode(' · ', $parts);
    };
    [$heading, $intro, $btnUrl, $btnLabel] = match ($audience) {
        'admin' => ['New order #' . $order->id, $dealerName . ' placed an order. It is waiting in Pending.', route('dashboard.orders.print', $order->id), 'View the invoice'],
        'rep' => ['New order from ' . $dealerName, 'One of your dealers placed order #' . $order->id . '.', route('representative.dashboard'), 'Open your dashboard'],
        default => ['Thanks, we received your order', 'Order #' . $order->id . ' is in. We will review it and let you know as soon as it moves to processing.', route('dealer.orders.print', $order->id), 'View the invoice'],
    };
@endphp

@section('preheader', 'Order #' . $order->id . ' · ' . number_format((int) $items->sum('quantity')) . ' items · ' . $money($order->total))
@section('eyebrow', 'Order #' . $order->id)
@section('heading', $heading)

@section('content')
    <p style="margin: 0;">{{ $intro }}</p>

    @include('emails.partials.details', ['rows' => [
        'Dealer' => $audience === 'dealer' ? null : $dealerName,
        'Order' => '#' . $order->id,
        'PO number' => $order->po_number,
        'Placed' => $order->created_at?->format('M j, Y · g:i A'),
        'Placed by' => $audience === 'dealer' ? null : $placedBy,
    ]])

    {{-- Where it goes (the team prepares and ships from this) --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 18px 0 0;">
        <tr>
            <td width="50%" style="padding: 0 12px 0 0; vertical-align: top; font-family: Helvetica, Arial, sans-serif;">
                <p style="margin: 0 0 4px; font-size: 11px; font-weight: bold; letter-spacing: 1.2px; text-transform: uppercase; color: #8C8178;">Bill to</p>
                <p style="margin: 0; font-size: 14px; line-height: 1.5; color: #231A14;"><strong>{{ $dealerName }}</strong>@if ($dealer?->name && $dealer?->company_name)<br>{{ $dealer->name }}@endif
                    @foreach ($addressLines as $line)<br>{{ $line }}@endforeach
                </p>
            </td>
            <td width="50%" style="padding: 0 0 0 12px; vertical-align: top; font-family: Helvetica, Arial, sans-serif;">
                <p style="margin: 0 0 4px; font-size: 11px; font-weight: bold; letter-spacing: 1.2px; text-transform: uppercase; color: #8C8178;">Ship to</p>
                <p style="margin: 0; font-size: 14px; line-height: 1.5; color: #231A14;"><strong>{{ $dealerName }}</strong>
                    @foreach ($addressLines as $line)<br>{{ $line }}@endforeach
                    @if ($dealer?->phone)<br>{{ $dealer->phone }}@endif
                </p>
            </td>
        </tr>
    </table>

    {{-- Lines (first 10) --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 18px 0 0;">
        @foreach ($shown as $item)
            @php $photo = $item->displayImage(); @endphp
            <tr>
                <td width="64" style="padding: 10px 12px 10px 0; border-bottom: 1px solid #EFE6D8; vertical-align: top;">
                    @if ($photo)
                        <img src="{{ thumb($photo, 240) }}" alt="" width="56" height="56" style="display: block; width: 56px; height: 56px; object-fit: cover; border-radius: 6px; border: 1px solid #E3D8C8;">
                    @endif
                </td>
                <td style="padding: 10px 0; border-bottom: 1px solid #EFE6D8; font-family: Helvetica, Arial, sans-serif; vertical-align: top;">
                    <span style="font-size: 14px; color: #231A14; font-weight: bold;">{{ $item->name }}</span><br>
                    <span style="font-size: 12.5px; color: #8C8178;">{{ trim(implode(' · ', array_filter([$item->sku ? 'SKU ' . $item->sku : null, $specs($item)]))) }}</span>
                </td>
                <td style="padding: 10px 0 10px 12px; border-bottom: 1px solid #EFE6D8; font-family: Helvetica, Arial, sans-serif; font-size: 13px; color: #5F534A; white-space: nowrap; text-align: right; vertical-align: top;">
                    {{ (int) $item->quantity }} × {{ $money($item->price) }}<br>
                    <strong style="color: #231A14; font-size: 14px;">{{ $money($item->total) }}</strong>
                </td>
            </tr>
        @endforeach
        @if ($more > 0)
            <tr>
                <td colspan="3" style="padding: 10px 0; border-bottom: 1px solid #EFE6D8; font-family: Helvetica, Arial, sans-serif; font-size: 13px; color: #5F534A;">
                    and {{ $more }} more {{ \Illuminate\Support\Str::plural('line', $more) }} — see the full order.
                </td>
            </tr>
        @endif
        <tr>
            <td colspan="2" style="padding: 14px 0 0; font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: #231A14;">
                <strong>Total</strong> <span style="color: #8C8178; font-size: 13px;">· {{ number_format((int) $items->sum('quantity')) }} items</span>
            </td>
            <td style="padding: 14px 0 0; font-family: Georgia, serif; font-size: 22px; color: #7B2D26; text-align: right; white-space: nowrap;">{{ $money($order->total) }}</td>
        </tr>
    </table>

    @include('emails.partials.button', ['url' => $btnUrl, 'label' => $btnLabel])
    @if ($audience === 'admin')
        <p style="margin: 6px 0 0; font-size: 13px;"><a href="{{ route('dashboard.orders.index', ['order' => $order->id]) }}" style="color: #7B2D26;">Open the order in the dashboard</a> to change its status.</p>
    @endif
@endsection
