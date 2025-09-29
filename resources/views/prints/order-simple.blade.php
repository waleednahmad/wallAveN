<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order {{ $order->id }} - Simple PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .logo {
            max-width: 150px;
            max-height: 80px;
            margin-bottom: 10px;
        }
        .company-info, .order-info {
            margin-bottom: 20px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
            width: 50%;
        }
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .order-table th,
        .order-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .order-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .status {
            text-transform: uppercase;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($logoImage)
            <img src="{{ $logoImage }}" alt="Company Logo" class="logo">
        @endif
        <h1>ORDER INVOICE</h1>
        <p>Order #{{ $order->id }}</p>
    </div>

    <div class="company-info">
        <table class="info-table">
            <tr>
                <td>
                    <strong>Bill To:</strong><br>
                    {{ $order->dealer->company_name }}<br>
                    {{ $address }}<br>
                    {{ $city }}{{ $city != '---' ? ', ' : '' }}{{ $state }}{{ $state != '---' ? ' ' : '' }}{{ $zip_code }}<br>
                    Phone: {{ $phone }}
                </td>
                <td>
                    <strong>Ship To:</strong><br>
                    {{ $order->dealer->company_name }}<br>
                    {{ $address }}<br>
                    {{ $city }}{{ $city != '---' ? ', ' : '' }}{{ $state }}{{ $state != '---' ? ' ' : '' }}{{ $zip_code }}
                </td>
            </tr>
        </table>
    </div>

    <div class="order-info">
        <table class="info-table">
            <tr>
                <td>
                    <strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}<br>
                    <strong>Status:</strong> <span class="status">{{ $order->status }}</span>
                </td>
                <td class="text-right">
                    <strong>Total Quantity:</strong> {{ $order->quantity }}<br>
                    <strong>Total Amount:</strong> ${{ number_format($order->total, 2) }}
                </td>
            </tr>
        </table>
    </div>

    @if($order->more_info)
    <div style="margin-bottom: 20px;">
        <strong>Additional Information:</strong><br>
        {{ $order->more_info }}
    </div>
    @endif

    @if($order->orderItems && $order->orderItems->count() > 0)
    <table class="order-table">
        <thead>
            <tr>
                <th style="width: 60px;">Image</th>
                <th>Product</th>
                <th style="width: 80px;">Quantity</th>
                <th style="width: 100px;">Unit Price</th>
                <th style="width: 100px;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>
                    @if($item->product && $item->product->image)
                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                             alt="{{ $item->product->name ?? 'Product' }}" 
                             style="width: 50px; height: 50px; object-fit: cover;">
                    @else
                        <div style="width: 50px; height: 50px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 10px;">No Image</div>
                    @endif
                </td>
                <td>
                    <strong>{{ $item->product->name ?? 'Product' }}</strong>
                    @if($item->item_type == 'variant' && $item->attributes)
                        @php
                            $attributes = json_decode($item->attributes, true);
                            $attributes = array_filter($attributes, function($value) {
                                return strtolower($value) !== 'none';
                            });
                        @endphp
                        @if(!empty($attributes))
                            <br><small>
                                @foreach($attributes as $key => $value)
                                    {{ ucfirst($key) }}: {{ $value }}
                                    @if(!$loop->last), @endif
                                @endforeach
                            </small>
                        @endif
                    @endif
                </td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">${{ number_format($item->price, 2) }}</td>
                <td class="text-right">${{ number_format($item->quantity * $item->price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3"></td>
                <td class="text-right">TOTAL:</td>
                <td class="text-right">${{ number_format($order->total, 2) }}</td>
            </tr>
        </tfoot>
    </table>
    @endif

    <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #666;">
        Generated on {{ now()->format('M d, Y \a\t H:i A') }}
    </div>
</body>
</html>