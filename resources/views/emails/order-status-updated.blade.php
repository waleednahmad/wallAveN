@extends('emails.layout')
@php
    $status = strtolower((string) $order->status);
    $lines = [
        'processing' => 'We are preparing your order now.',
        'completed' => 'Your order is complete. Thank you for ordering with us.',
        'declined' => 'We could not accept this order. Please get in touch if you have questions.',
        'canceled' => 'This order was canceled.',
        'pending' => 'Your order is back in our queue and will be reviewed shortly.',
    ];
    $tone = ['processing' => ['#2B4466', '#DCE6F2'], 'completed' => ['#2F7A4A', '#DDEFE3'], 'declined' => ['#A3392E', '#F6DEDA'], 'canceled' => ['#5F534A', '#EAE5DE']][$status] ?? ['#8A6116', '#F6E7C8'];
@endphp

@section('preheader', 'Order #' . $order->id . ' is now ' . $status . '. ' . ($lines[$status] ?? ''))
@section('eyebrow', 'Order update')
@section('heading', 'Order #' . $order->id . ' is now ' . $status)

@section('content')
    <p style="margin: 0 0 14px;">
        <span style="display: inline-block; padding: 4px 12px; border-radius: 12px; background: {{ $tone[1] }}; color: {{ $tone[0] }}; font-size: 12px; font-weight: bold; letter-spacing: .5px; text-transform: uppercase;">{{ ucfirst($status) }}</span>
    </p>
    <p style="margin: 0;">Hello {{ $order->dealer?->name ?: ($order->dealer?->company_name ?: 'there') }}, {{ $lines[$status] ?? 'The status of your order changed.' }}</p>
    @include('emails.partials.details', ['rows' => [
        'Order' => '#' . $order->id,
        'PO number' => $order->po_number,
        'Placed' => $order->created_at?->format('M j, Y'),
        'Items' => number_format((int) $order->quantity),
        'Total' => '$' . number_format((float) $order->total, 2),
    ]])
    @include('emails.partials.button', ['url' => route('dealer.orders'), 'label' => 'View your orders'])
@endsection
