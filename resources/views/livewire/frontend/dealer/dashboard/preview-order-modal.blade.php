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
                {{-- ============ Previrer loading spinner ======== --}}
                <div class="text-center col-12" wire:loading>
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.col -->
</div>
