<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>New Order Placed - {{ getWebsiteTitle() }}</title>

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; text-align: center;">
    <div style="max-width: 900px; background: #ffffff; padding: 20px; border-radius: 5px; margin: auto;">
        <img src="{{ getMainImage() }}" alt="{{ getWebsiteTitle() }}" style="max-width: 200px;">
        <h2 style="color: #333;">New Order Placed</h2>
        <p style="font-size: 16px; color: #555;">
            A new order has been placed on the <strong>{{ getWebsiteTitle() }}</strong> website.
        </p>

        <div class="row">
            <div class="col-12">
                <!-- Main content -->
                <div class="p-3 mb-3 invoice">
                    <!-- title row -->
                    <div class="row">
                        <div class="col-12">
                            <h4 class="py-2 d-flex align-items-center justify-content-between">
                                Golden Rugs.
                                <small class="float-right">
                                    <b>Ordered At:</b>
                                    @if ($order?->created_at)
                                        {{ $order?->created_at->format('m/d/Y') }}
                                    @endif
                                    <span class="ps-4">
                                        <b>Invoice #</b>{{ $order?->id }}
                                    </span>
                                </small>
                            </h4>

                            @if ($order?->status == 'pending')
                                <h5 class="badge bg-warning">Pending</h5>
                            @elseif ($order?->status == 'processing')
                                <h5 class="badge bg-primary">Processing</h5>
                            @elseif ($order?->status == 'completed')
                                <h5 class="badge bg-success">Completed</h5>
                            @elseif ($order?->status == 'declined')
                                <h5 class="badge bg-danger">Declined</h5>
                            @elseif ($order?->status == 'canceled')
                                <h5 class="badge bg-secondary">Canceled</h5>
                            @endif
                        </div>
                        <!-- /.col -->
                    </div>

                    <!-- Table row -->

                    @if (isset($items) && count($items) > 0)
                        <div style="display: flex; justify-content: center;">
                            <div style="width: 100%; overflow-x: auto;">
                                <table style="width: 100%; border: 1px solid #ddd; border-collapse: collapse; margin: auto;">
                                    <thead>
                                        <tr>
                                            <th style="border: 1px solid #ddd; padding: 3px;">#</th>
                                            <th style="border: 1px solid #ddd; padding: 3px;">Image</th>
                                            <th style="border: 1px solid #ddd; padding: 3px;">SKU</th>
                                            <th style="border: 1px solid #ddd; padding: 3px;">Name</th>
                                            <th style="border: 1px solid #ddd; padding: 3px;">Qty</th>
                                            <th style="border: 1px solid #ddd; padding: 3px;">Attributes</th>
                                            <th style="border: 1px solid #ddd; padding: 3px;">Price/Unit</th>
                                            <th style="border: 1px solid #ddd; padding: 3px;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                            <tr>
                                                <td style="border: 1px solid #ddd; padding: 3px;">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td style="border: 1px solid #ddd; padding: 3px;">
                                                    @if ($item->image && file_exists($item->image))
                                                        <img src="{{ asset($item->image) }}" alt="{{ $item->name }}"
                                                            style="width: 70px; height: 70px; border-radius: 15px; object-fit: contain;">
                                                    @else
                                                        <img src="{{ asset($item->product->image) }}"
                                                            alt="{{ $item->name }}"
                                                            style="width: 70px; height: 70px; border-radius: 15px; object-fit: contain;">
                                                    @endif
                                                </td>
                                                <td style="border: 1px solid #ddd; padding: 3px; max-width: fit-content;">
                                                    {{ $item->sku ?? '---' }}
                                                </td>
                                                <td style="border: 1px solid #ddd; padding: 3px; max-width: 110px; word-wrap: break-word;">
                                                    {{ $item->name ?? '---' }}
                                                </td>
                                                <td style="border: 1px solid #ddd; padding: 3px;">
                                                    {{ $item->quantity }}
                                                </td>
                                                <td style="border: 1px solid #ddd; padding: 3px;">
                                                    @if ($item->item_type == 'variant')
                                                        @php
                                                            $attributes = json_decode($item->attributes, true);
                                                        @endphp
                                                        <table
                                                            style="border: 1px solid #ddd; border-collapse: collapse; width: 100%; margin-bottom: 0;">
                                                            <thead>
                                                                <tr>
                                                                    @foreach (array_keys($attributes) as $key)
                                                                        <th
                                                                            style="border: 1px solid #ddd; padding: 3px; text-align: left;">
                                                                            {{ ucwords($key) }}</th>
                                                                    @endforeach
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    @foreach (array_values($attributes) as $value)
                                                                        <td
                                                                            style="border: 1px solid #ddd; padding: 3px; text-align: left;">
                                                                            {{ ucwords($value) }}</td>
                                                                    @endforeach
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    @endif
                                                </td>
                                                <td style="border: 1px solid #ddd; padding: 3px;">
                                                    <b>$</b> {{ $item->price ?? '---' }}
                                                </td>
                                                <td style="border: 1px solid #ddd; padding: 3px;">
                                                    <b>$</b> {{ $item->total ?? '---' }}
                                                </td>
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td colspan="6" style="border: 1px solid #ddd; padding: 3px;"></td>
                                            <td style="border: 1px solid #ddd; padding: 3px;">
                                                <b>Total</b>
                                            </td>
                                            <td style="border: 1px solid #ddd; padding: 3px;">
                                                <b>$</b> {{ $order?->total }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div><!-- /.col -->
        </div>

        <br>
        <hr>
        <br>
        <p style="font-size: 16px; color: #555;">
            📍 <strong>Address:</strong> 4528 W 51st St, Chicago, IL 60632 <br>
            📞 <strong>Phone:</strong> <a href="tel:(773) 490-3801">(773) 490-3801</a> <br>
            ✉️ <strong>Email:</strong> <a href="mailto:{{ env('MAIL_FROM_ADDRESS', 'nidal@goldenrugsinc.com') }}"
                style="color: #d19c4b; text-decoration: none;">{{ env('MAIL_FROM_ADDRESS', 'nidal@goldenrugsinc.com') }}</a>
        </p>
        <p style="font-size: 16px; color: #555;">Best regards, <br> <strong>{{ getWebsiteTitle() }}</strong></p>
    </div>
</body>

</html>
