<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Print</title>
    <style type="text/css">
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #fff;
            color: #222;
            margin: 0;
            padding: 0;
        }

        .invoice {
            max-width: 900px;
            margin: 30px auto;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 30px 40px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        .bill-ship {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            margin-top: 15px;
            border-radius: 5px;
            overflow: hidden;
            text-align: left;
            /* CSS2 float-based layout for 3 sections in one line */
        }

        .bill-ship section {
            float: left;
            width: 32%;
            margin-right: 2%;
            box-sizing: border-box;
        }

        .bill-ship section:last-child {
            margin-right: 0;
        }

        /* Clearfix for .bill-ship */
        .bill-ship:after {
            content: "";
            display: table;
            clear: both;
        }

        .bill-ship table {
            width: 100%;
            border-collapse: collapse;
        }

        .bill-ship td,
        .bill-ship th {
            font-size: 14px;
            padding: 2px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 14px;
        }

        .table th {
            background: #f7f7f7;
        }

        .img-thumbnail {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .text-end {
            text-align: right;
        }

        h6 {
            margin: 0;
        }

        @media print {
            body {
                background: #fff;
            }

            .invoice {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>

<body>
    <div class="invoice" id="printableArea">
        <header>
            {{-- <img src="{{ getMainImage() }}" alt="{{ getWebsiteTitle() }}" style="max-width: 200px;"> --}}
            <div class="bill-ship">
                <section>
                    <p>
                        Name: <br>
                        <strong>{{ $order?->dealer->company_name }}</strong><br>
                        <span
                            style="color: #333; display: block; margin-bottom: 4px; font-size: 14px; margin-top: 7px;">BILL
                            TO</span>
                        <strong>
                            {{ $address }}<br>
                            {{ $city }}{{ $city != '---' ? ',' : '' }}
                            {{ $state }}{{ $state != '---' ? ' ' : '' }}
                            {{ $zip_code }}
                        </strong>
                        @if ($order?->more_info)
                            <hr>
                            <u>More Info :</u>
                            <strong>{{ $order?->more_info }}</strong>
                        @endif
                    </p>
                </section>
                <section>
                    <p>
                        Phone: <br>
                        <strong><a href="tel:{{ $phone }}">{{ $phone }}</a></strong><br>
                        <span
                            style="color: #333; display: block; margin-bottom: 4px; font-size: 14px; margin-top: 7px;">SHIP
                            TO</span>
                        <strong>
                            {{ $address }}<br>
                            {{ $city }}{{ $city != '---' ? ',' : '' }}
                            {{ $state }}{{ $state != '---' ? ' ' : '' }}
                            {{ $zip_code }}
                        </strong>
                    </p>
                </section>
                <section>
                    <table style="padding-top: 20px">
                        <tr>
                            <td>INVOICE</td>
                            <th>{{ $order?->id }}</th>
                        </tr>
                        <tr>
                            <td>DATE</td>
                            <th>{{ $order?->created_at->format('m/d/Y') }}</th>
                        </tr>
                        <tr>
                            <td>DUE DATE</td>
                            <th>{{ $order?->created_at->format('m/d/Y') }}</th>
                        </tr>
                        <tr>
                            <td>PLACED BY</td>
                            @if ($order?->representative_id)
                                <th>Sales Rep</th>
                            @elseif($order?->admin_id)
                                <th>Admin</th>
                            @else
                                <th>Dealer</th>
                            @endif
                        </tr>
                        <tr>
                            <td>STATUS</td>
                            <th style="text-transform: capitalize;">{{ $order?->status }}</th>
                        </tr>
                    </table>
                </section>
            </div>
        </header>
        @if (isset($order) && $order->orderItems)
            @php
                $items = $order->orderItems;
                $attributeKeys = [];
                foreach ($items as $item) {
                    if ($item->item_type == 'variant') {
                        $attributes = json_decode($item->attributes, true);
                        foreach ($attributes as $key => $value) {
                            if (!in_array($key, $attributeKeys)) {
                                $attributeKeys[] = $key;
                            }
                        }
                    }
                }
            @endphp
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>SKU</th>
                        <th>Name</th>
                        @foreach ($attributeKeys as $key)
                            <th>{{ ucwords($key) }}</th>
                        @endforeach
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $itemsQuantity = 0;
                    @endphp
                    @foreach ($order?->orderItems as $item)
                        @php
                            $itemsQuantity += $item->quantity;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if ($item->image && file_exists($item->image))
                                    <img src="{{ asset($item->image) }}" alt="{{ $item->name }}"
                                        class="img-thumbnail">
                                @else
                                    <img src="{{ asset('dashboard/images/default.webp') }}" alt="{{ $item->name }}"
                                        class="img-thumbnail">
                                @endif
                            </td>
                            <td><small>{{ $item->sku ?? '---' }}</small></td>
                            <td>
                                @php
                                    $slug = $item?->product?->slug;
                                @endphp
                                <a href="{{ route('frontend.product', $slug) }}" target="_blank">
                                    {{ $item->name ?? '---' }}
                                </a>
                            </td>
                            @foreach ($attributeKeys as $key)
                                <td>
                                    @php $value = json_decode($item->attributes, true)[$key] ?? ' '; @endphp
                                    {{ $value }}
                                </td>
                            @endforeach
                            <td>{{ $item->quantity }}</td>
                            <td><b>$</b>{{ number_format($item->price, 2) ?? '---' }}</td>
                            <td><b>$</b>{{ number_format($item->total, 2) ?? '---' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-end">
                <h6>Total Qty : {{ $itemsQuantity }}</h6>
                <h6>Total : <b>$</b>{{ number_format($order?->total, 2) }}</h6>
            </div>
        @endif
    </div>

    <script>
        window.onload = function() {
            window.addEventListener('afterprint', function() {
                // Close the window after Print
                window.close();
            });

            printDiv('printableArea');

            function printDiv(divId) {
                var printContents = document.getElementById(divId).innerHTML;
                var originalContents = document.body.innerHTML;

                document.body.innerHTML = originalContents;
                window.print();
                document.body.innerHTML = originalContents;
            }

        }
    </script>
</body>

</html>
