<div class="row">
    <div class="col-12">
        <!-- Main content -->
        <div class="p-3 mb-3 invoice">
            <!-- title row -->
            <div class="row">
                <div class="col-12">
                    <h4 class="py-2">
                        <i class="fas fa-globe"></i> Golden Rugs.
                        <small class="float-right">
                            <b>Ordered At:</b>
                            @if ($order?->created_at)
                                {{ $order?->created_at->format('m/d/Y') }}
                            @endif
                            <span class="pl-4">
                                <b>Invoice #</b>{{ $order?->id }}
                            </span>
                        </small>
                    </h4>
                </div>
                <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    <address>
                        <b>Name</b>: {{ $order?->dealer->name ?? '---' }}<br>
                        <b>Email</b>: {{ $order?->dealer->email ?? '---' }}<br>
                        <b>Phone</b>: {{ $order?->dealer->phone ?? '---' }}<br>
                        <b>Address</b> <br>
                        @php
                            $address = $order?->dealer->address ? explode(',', $order->dealer->address)[0] : '---';
                            $city = $order?->dealer->city ?? '---';
                            $state = $order?->dealer->state ?? '---';
                            $zip_code = $order?->dealer->zip_code ?? '---';
                        @endphp
                        {{ $address }}<br>
                        {{ $city }}{{ $city != '---' ? ',' : '' }}
                        {{ $state }}{{ $state != '---' ? ' ' : '' }}
                        {{ $zip_code }}
                    </address>

                    @if ($order?->more_info)
                        {{-- preview more_info if exists --}}
                        <hr>
                        <h5>
                            <u>
                                More Info :
                            </u>
                        </h5>
                        <address>
                            {{ $order?->more_info }}
                        </address>
                    @endif
                </div>
                <!-- /.col -->

            </div>
            <!-- /.row -->

            <!-- Table row -->


            @if (isset($order) && $order->orderItems)
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
                                    <th>
                                        Attributes
                                    </th>
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
                                        <td style="max-width: fit-content">
                                            {{ $item->sku ?? '---' }}
                                        </td>
                                        <td style="max-width: 110px; text-wrap: pretty;">
                                            {{ $item->name ?? '---' }}
                                        </td>

                                        <td>
                                            {{ $item->quantity }}
                                        </td>
                                        <td>
                                            @if ($item->item_type == 'variant')
                                                @php
                                                    $attributes = json_decode($item->attributes, true);
                                                @endphp
                                                <table class="table table-bordered table-sm mb-0">
                                                    <thead>
                                                        <tr>
                                                            @foreach (array_keys($attributes) as $key)
                                                                <th>{{ ucwords($key) }}</th>
                                                            @endforeach
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            @foreach (array_values($attributes) as $value)
                                                                <td>{{ ucwords($value) }}</td>
                                                            @endforeach
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            @endif
                                        </td>
                                        <td>
                                            <b>$</b> {{ $item->price ?? '---' }}
                                        </td>
                                        <td>
                                            <b>$</b> {{ $item->total ?? '---' }}
                                        </td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td colspan="6"></td>
                                    <td>
                                        <b>Total</b>
                                    </td>
                                    <td>
                                        <b>$</b> {{ $order?->total }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.col -->
                </div>
            @endif

            <div class="row">
                <!-- accepted payments column -->
                {{-- <div class="col-6">
                    <p class="lead">Payment Methods:</p>
                    <img src="{{ asset('dashboard/dist/img/credit/visa.png') }}" alt="Visa">
                    <img src="{{ asset('dashboard/dist/img/credit/mastercard.png') }}" alt="Mastercard">
                    <img src="{{ asset('dashboard/dist/img/credit/american-express.png') }}" alt="American Express">
                    <img src="{{ asset('dashboard/dist/img/credit/paypal2.png') }}" alt="Paypal">

                    <p class="shadow-none text-muted well well-sm" style="margin-top: 10px;">
                        Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya
                        handango imeem
                        plugg
                        dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                    </p>
                </div> --}}
                <!-- /.col -->

                {{-- ============ Previrer loading spinner ======== --}}
                <div class="text-center col-12" wire:loading>
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <!-- /.row -->

        </div>
        <!-- /.invoice -->
    </div><!-- /.col -->
</div>
