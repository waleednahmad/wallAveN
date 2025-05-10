<div>
    <div class="row">
        <div class="col-12">
            <!-- Main content -->
            <div class="p-3 mb-3 invoice">
                @php
                    $name = $dealer?->company_name ?? '---';
                    $phone = $dealer?->phone ?? '---';
                    $address = $dealer?->address ? explode(',', $dealer?->address)[0] : '---';
                    $city = $dealer?->city ?? '---';
                    $state = $dealer?->state ?? '---';
                    $zip_code = $dealer?->zip_code ?? '---';
                    $placedBy = '';
                @endphp

                <header style="text-align: center; margin-bottom: 20px;">
                    <img src="{{ getMainImage() }}" alt="{{ getWebsiteTitle() }}" style="max-width: 200px;">

                    <div
                        style="border: 1px solid #ddd; padding: 10px; margin-bottom: 20px ; margin-top: 15px; border-radius: 5px; overflow: hidden; text-align: left !important;">
                        {{-- Bill to --}}
                        <section style="float: left; width: 30%; margin-right: 2%;">
                            <p>
                                Name: <br>
                                <strong>
                                    {{ $dealer?->company_name }}
                                </strong>
                            </p>
                        </section>
                        <section style="float: left; width: 30%; margin-right: 2%;">
                            <p>
                                Phone: <br>
                                <strong>
                                    <a href="tel:{{ $phone }}">{{ $phone }}</a>
                                </strong>
                            </p>
                        </section>
                        <section style="float: left; width: 30%; margin-right: 2%;">
                            <p>
                                Address: <br>
                                <strong>
                                    {{ $address }}<br>
                                    {{ $city }}{{ $city != '---' ? ',' : '' }}
                                    {{ $state }}{{ $state != '---' ? ' ' : '' }}
                                    {{ $zip_code }}
                                </strong>
                            </p>
                        </section>
                    </div>
                    <br>
                </header>
                <!-- Table row -->
                @if (isset($orderItems) && $orderItems)
                    @php
                        $attributeKeys = [];
                        $attributeValues = [];
                        foreach ($orderItems as $item) {
                            if ($item->item_type == 'variant') {
                                $attributes = json_decode($item->attributes, true);
                                $attributes = array_filter($attributes, function ($value) {
                                    return strtolower($value) !== 'none';
                                });
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
                                        @foreach ($attributeKeys as $key)
                                            <th>
                                                {{ ucwords($key) }}
                                            </th>
                                        @endforeach
                                        <th>Qty</th>
                                        <th>
                                            Price
                                        </th>
                                        <th>
                                            Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $itemsQuantity = 0;
                                    @endphp
                                    @foreach ($orderItems as $item)
                                        @php
                                            $itemsQuantity += $item->quantity;
                                        @endphp
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
                                            <td style="max-width: fit-content ; font-size:14px; text-wrap: pretty;">

                                                <small>
                                                    {{ $item->sku ?? '---' }}
                                                </small>
                                            </td>
                                            <td style="max-width: 110px; text-wrap: pretty;">
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
                                            <td>
                                                {{ $item->quantity }}
                                            </td>
                                            <td>
                                                <b>$</b>{{ number_format($item->price, 2) ?? '' }}
                                            </td>
                                            <td>
                                                <b>$</b>{{ number_format($item->total, 2) ?? '' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>
                                            <b>
                                                Total
                                            </b>
                                        </td>
                                        <td colspan="{{ 3 + count($attributeKeys) }}"></td>
                                        <td><b>{{ $itemsQuantity }}</b></td>
                                        <td></td>
                                        <td><b>${{ number_format($orderItems->sum('total'), 2) ?? '' }}</b></td>
                                    </tr>
                                </tfoot>
                            </table>
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
