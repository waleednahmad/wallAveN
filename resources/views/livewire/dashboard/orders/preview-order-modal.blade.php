<div>
    <div class="row">
        <div class="col-12">
            <!-- Main content -->
            <div class="p-3 mb-3 invoice">
                @php
                    $name = $order?->dealer->company_name ?? '---';
                    $phone = $order?->dealer->phone ?? '---';
                    $address = $order?->dealer->address ? explode(',', $order->dealer->address)[0] : '---';
                    $city = $order?->dealer->city ?? '---';
                    $state = $order?->dealer->state ?? '---';
                    $zip_code = $order?->dealer->zip_code ?? '---';
                    $placedBy = '';
                @endphp

                <header style="text-align: center; margin-bottom: 20px;">
                    <img src="{{ getMainImage() }}" alt="{{ getWebsiteTitle() }}" style="max-width: 200px;">

                    <div
                        style="border: 1px solid #ddd; padding: 10px; margin-bottom: 20px ; margin-top: 15px; border-radius: 5px; overflow: hidden; text-align: left !important;">
                        {{-- Bill to --}}
                        <section style="float: left; width: 32%; margin-right: 2%;">
                            <p>
                                Name: <br>
                                <strong>
                                    {{ $order?->dealer->company_name }}
                                </strong>
                                <br>
                                <span
                                    style=" color: #333; display: block; margin-bottom: 4px; font-size: 14px; margin-top: 7px;">
                                    BILL TO
                                </span>
                                <strong>
                                    {{ $address }}<br>
                                    {{ $city }}{{ $city != '---' ? ',' : '' }}
                                    {{ $state }}{{ $state != '---' ? ' ' : '' }}
                                    {{ $zip_code }}
                                </strong>

                                @if ($order?->more_info)
                                    {{-- preview more_info if exists --}}
                                    <hr>
                                    <u>
                                        More Info :
                                    </u>
                                    <strong>
                                        {{ $order?->more_info }}
                                    </strong>
                                @endif
                            </p>
                        </section>
                        <section style="float: left; width: 32%; margin-right: 2%;">
                            <p>
                                Phone: <br>
                                <strong>
                                    <a href="tel:{{ $phone }}">{{ $phone }}</a>
                                </strong>
                                <br>
                                <span
                                    style=" color: #333; display: block; margin-bottom: 4px; font-size: 14px; margin-top: 7px;">
                                    SHIP TO
                                </span>
                                <strong>
                                    {{ $address }}<br>
                                    {{ $city }}{{ $city != '---' ? ',' : '' }}
                                    {{ $state }}{{ $state != '---' ? ' ' : '' }}
                                    {{ $zip_code }}
                                </strong>
                            </p>
                        </section>
                        <section style="float: left; width: 32%;">
                            <table style="text-align: left; width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="font-size: 14px;">INVOICE</td>
                                    <th>{{ $order?->id }}</th>
                                </tr>
                                <tr>
                                    <td style="font-size: 14px;">DATE</td>
                                    <th>{{ $order?->created_at->format('m/d/Y') }}</th>
                                </tr>
                                <tr>
                                    <td style="font-size: 14px;">DUE DATE</td>
                                    <th>{{ $order?->created_at->format('m/d/Y') }}</th>
                                </tr>
                                <tr>
                                    <td style="font-size: 14px;">PLACED BY</td>

                                    @if ($order?->representative_id)
                                        <th>
                                            Sales Rep
                                        </th>
                                    @elseif($order?->admin_id)
                                        <th>
                                            Admin
                                        </th>
                                    @else
                                        <th>
                                            Dealer
                                        </th>
                                    @endif
                                </tr>
                                {{-- status --}}
                                <tr>
                                    <td style="font-size: 14px;">STATUS</td>
                                    <th style="text-transform: capitalize;">
                                        {{ $order?->status }}
                                    </th>

                                </tr>
                            </table>
                        </section>
                        <div style="clear: both;"></div>
                    </div>
                    <br>
                </header>
                <!-- Table row -->
                @if (isset($order) && $order->orderItems)
                    @php
                        $items = $order->orderItems;
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
                    <div class="row" wire:loading.remove>
                        <div class="col-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>
                                            Image
                                        </th>
                                        <th>SKU</th>
                                        <th>Name</th>
                                        <th>Qty</th>
                                        @foreach ($attributeKeys as $key)
                                            <th>
                                                {{ ucwords($key) }}
                                            </th>
                                        @endforeach
                                        <th>
                                            Price/Unit
                                        </th>
                                        <th>
                                            Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order?->orderItems as $item)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                @if ($item->image && file_exists($item->image))
                                                    <img src="{{ asset($item->image) }}" alt="{{ $item->name }}"
                                                        class="img-thumbnail" style="width: 90px; height: 90px;">
                                                @else
                                                    <img src="{{ asset('dashboard/images/default.webp') }}"
                                                        alt="{{ $item->name }}" class="img-thumbnail"
                                                        style="width: 90px; height: 90px;">
                                                @endif
                                            </td>
                                            <td
                                                style="max-width: fit-content ; font-size:14px; text-wrap: pretty;">

                                                <small>
                                                    {{ $item->sku ?? '---' }}
                                                </small>
                                            </td>
                                            <td style="max-width: 110px; text-wrap: pretty;">
                                                {{ $item->name ?? '---' }}
                                            </td>

                                            <td>
                                                {{ $item->quantity }}
                                            </td>
                                            @foreach ($attributeKeys as $key)
                                                <td style="border: 1px solid #ddd; padding: 3px;">
                                                    @php
                                                        $value = json_decode($item->attributes, true)[$key] ?? ' ';
                                                    @endphp
                                                    {{ $value }}
                                                </td>
                                            @endforeach
                                            <td>
                                                <b>$</b>{{ number_format($item->price, 2) ?? '---' }}
                                            </td>
                                            <td>
                                                <b>$</b>{{ number_format($item->total, 2) ?? '---' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pe-3">
                                <h6 class="text-end">
                                    <b>Total</b>
                                    <b>$</b>{{ number_format($order?->total, 2) }}
                                </h6>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                @endif
                <div class="row">
                    {{-- ============ Previrer loading spinner ======== --}}
                    <div class="text-center col-12" wire:loading>
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
        </div>
    </div>
</div>
