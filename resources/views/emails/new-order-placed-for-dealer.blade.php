<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>New Order Placed - {{ getWebsiteTitle() }}</title>

    <style>
        .text-end {
            text-align: right;
        }
    </style>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; text-align: center;">
    <div style="max-width: 900px; background: #ffffff; padding: 20px; border-radius: 5px; margin: auto;">
        <header style="text-align: center; margin-bottom: 20px;">
            <img src="{{ getMainImage() }}" alt="{{ getWebsiteTitle() }}" style="max-width: 200px;">
            <h2 style="color: #333;">New Order Placed</h2>
            <p style="font-size: 16px; color: #555;">
                A new order has been placed on the <strong>{{ getWebsiteTitle() }}</strong> website.
            </p>

        </header>
        @php
            $address = $order?->dealer->address ? explode(',', $order->dealer->address)[0] : '---';
            $city = $order?->dealer->city ?? '---';
            $state = $order?->dealer->state ?? '---';
            $zip_code = $order?->dealer->zip_code ?? '---';
        @endphp


        <div
            style="border: 1px solid #ddd; padding: 10px; margin-bottom: 20px; border-radius: 5px; overflow: hidden; text-align: left !important;">
            {{-- Bill to --}}
            <section style="float: left; width: 32%; margin-right: 2%;">
                <p>
                    <strong>Name:</strong> {{ $name }} <br>
                    <span
                        style="font-weight: bold; color: #333; display: block; margin-bottom: 4px; font-size: 14px; margin-top: 7px;">
                        BILL TO
                    </span>
                    {{ $address }}<br>
                    {{ $city }}{{ $city != '---' ? ',' : '' }}
                    {{ $state }}{{ $state != '---' ? ' ' : '' }}
                    {{ $zip_code }}
                </p>
            </section>
            <section style="float: left; width: 32%; margin-right: 2%;">
                <p>
                    <strong>Phone:</strong> <a href="tel:{{ $phone }}">{{ $phone }}</a> <br>
                    <span
                        style="font-weight: bold; color: #333; display: block; margin-bottom: 4px; font-size: 14px; margin-top: 7px;">
                        SHIP TO
                    </span>
                    {{ $address }}<br>
                    {{ $city }}{{ $city != '---' ? ',' : '' }}
                    {{ $state }}{{ $state != '---' ? ' ' : '' }}
                    {{ $zip_code }}
                </p>
            </section>
            <section style="float: left; width: 32%;">
                <table style="text-align: left; width: 100%; border-collapse: collapse;">
                    <tr>
                        <th style="font-size: 14px;">INVOICE</th>
                        <td>{{ $order?->id }}</td>
                    </tr>
                    <tr>
                        <th style="font-size: 14px;">DATE</th>
                        <td>{{ $order?->created_at->format('m/d/Y') }}</td>
                    </tr>
                    <tr>
                        <th style="font-size: 14px;">DUE DATE</th>
                        <td>{{ $order?->created_at->format('m/d/Y') }}</td>
                    </tr>
                    <tr>
                        <th style="font-size: 14px;">PLACED BY</th>
                        @if ($order?->representative_id)
                            <td>
                                Sales Rep
                            </td>
                        @elseif($order?->admin_id)
                            <td>
                                Admin
                            </td>
                        @else
                            <td>
                                Dealer
                            </td>
                        @endif
                    </tr>
                </table>
            </section>
            <div style="clear: both;"></div>
        </div>
        <br>


        <!-- Main content -->
        <div style="text-align: center;">
            <!-- title row -->
            {{-- <div class="row">
                        <div class="col-12">
                            <h4 class="py-2 d-flex align-items-center justify-content-between">
                                {{ getWebsiteTitle() }}.
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
                        </div>
                        <!-- /.col -->
                    </div> --}}

            <!-- Table row -->

            @if (isset($items) && count($items) > 0)
                @php
                    $attributeKeys = [];
                    $attributeValues = [];
                    foreach ($items as $item) {
                        if ($item->item_type == 'variant') {
                            $attributes = json_decode($item->attributes, true);
                            foreach ($attributes as $key => $value) {
                                if (!in_array($key, $attributeKeys)) {
                                    $attributeKeys[] = $key;
                                }
                                if (!in_array($value, $attributeValues)) {
                                    $attributeValues[] = $value;
                                }
                            }
                        }
                    }
                @endphp
                <div style="display: flex; justify-content: center;">
                    <div style="width: 100%; overflow-x: auto;">
                        <table style="width: 100%; border: 1px solid #ddd; border-collapse: collapse; margin: auto;">
                            <thead>
                                <tr>
                                    <th style="border: 1px solid #ddd; padding: 3px;">#</th>
                                    <th style="border: 1px solid #ddd; padding: 3px;">Image</th>
                                    <th style="border: 1px solid #ddd; padding: 3px;">SKU</th>
                                    <th style="border: 1px solid #ddd; padding: 3px;">Name</th>
                                    @foreach ($attributeKeys as $key)
                                        <th style="border: 1px solid #ddd; padding: 3px; ">
                                            {{ ucwords($key) }}</th>
                                    @endforeach

                                    <th style="border: 1px solid #ddd; padding: 3px;">Qty</th>
                                    <th style="border: 1px solid #ddd; padding: 3px;">Price</th>
                                    <th style="border: 1px solid #ddd; padding: 3px;">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $itemsQuantity = 0;
                                @endphp
                                @foreach ($items as $item)
                                    @php
                                        $itemsQuantity += $item->quantity;
                                    @endphp
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
                                            <small>
                                                {{ $item->sku ?? '---' }}
                                            </small>
                                        </td>
                                        <td
                                            style="border: 1px solid #ddd; padding: 3px; max-width: 110px; word-wrap: break-word;">
                                            @php
                                                $slug = $item?->product?->slug;
                                            @endphp
                                            <a href="{{ route('frontend.product', $slug) }}" target="_blank">
                                                {{ $item->name ?? '---' }}
                                            </a>
                                        </td>

                                        @foreach ($attributeKeys as $key)
                                            <td style="border: 1px solid #ddd; padding: 3px;">
                                                @php
                                                    $value = json_decode($item->attributes, true)[$key] ?? ' ';
                                                @endphp
                                                {{ $value }}
                                            </td>
                                        @endforeach
                                        <td style="border: 1px solid #ddd; padding: 3px;">
                                            {{ $item->quantity }}
                                        </td>
                                        <td style="border: 1px solid #ddd; padding: 3px;">
                                            <b>$</b>{{ $item->price ?? '---' }}
                                        </td>
                                        <td style="border: 1px solid #ddd; padding: 3px;">
                                            <b>$</b>{{ $item->total ?? '---' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="text-end">
                            <h6>Total Qty : {{ $itemsQuantity }}</h6>
                            <h6>Total : <b>$</b>{{ number_format($order?->total, 2) }}</h6>
                        </div>
                    </div>
                </div>
            @endif
        </div>


        <br>
        <hr>
        <br>
        <footer style="text-align: center;">
            <p style="font-size: 16px; color: #555;">
                📍 <strong>Address:</strong> 4528 W 51st St, Chicago, IL 60632 <br>
                📞 <strong>Phone:</strong> <a href="tel:(773) 490-3801">(773) 490-3801</a> <br>
                ✉️ <strong>Email:</strong> <a href="mailto:{{ env('MAIL_FROM_ADDRESS', 'nidal@goldenrugsinc.com') }}"
                    style="color: #d19c4b; text-decoration: none;">{{ env('MAIL_FROM_ADDRESS', 'nidal@goldenrugsinc.com') }}</a>
            </p>
            <p style="font-size: 16px; color: #555;">Best regards, <br> <strong>{{ getWebsiteTitle() }}</strong></p>
        </footer>
    </div>
</body>

</html>
