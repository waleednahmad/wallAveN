@extends('emails.layout')
@php
    // One email, three readers: the dealer, their sales rep and the admins
    $audience = $audience ?? 'dealer';
    $dealer = $order->dealer;
    $dealerName = $dealer?->company_name ?: ($dealer?->name ?: 'a dealer');
    $items = $order->orderItems;
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
        'admin' => ['New order #' . $order->id, $dealerName . ' placed an order. It is waiting in Pending.', route('dashboard.orders.index', ['order' => $order->id]), 'Open the order'],
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

    {{-- Lines (first 10) --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 18px 0 0;">
        @foreach ($shown as $item)
            <tr>
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
                <td colspan="2" style="padding: 10px 0; border-bottom: 1px solid #EFE6D8; font-family: Helvetica, Arial, sans-serif; font-size: 13px; color: #5F534A;">
                    and {{ $more }} more {{ \Illuminate\Support\Str::plural('line', $more) }} — see the full order.
                </td>
            </tr>
        @endif
        <tr>
            <td style="padding: 14px 0 0; font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: #231A14;">
                <strong>Total</strong> <span style="color: #8C8178; font-size: 13px;">· {{ number_format((int) $items->sum('quantity')) }} items</span>
            </td>
            <td style="padding: 14px 0 0; font-family: Georgia, serif; font-size: 22px; color: #7B2D26; text-align: right; white-space: nowrap;">{{ $money($order->total) }}</td>
        </tr>
    </table>

    @include('emails.partials.button', ['url' => $btnUrl, 'label' => $btnLabel])
@endsection
