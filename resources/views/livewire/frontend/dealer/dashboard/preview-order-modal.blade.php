<div class="row">
    <div class="col-12">
        <!-- Main content -->
        <div class="p-3 mb-3 invoice">
            <!-- title row -->
            <div class="row">
                <div class="col-12">
                    <h4 class="py-2 d-flex align-items-center justify-content-between">
                        Golden Ruggs.
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

            @if (isset($order) && $order->items)
                <div class="row" wire:loading.remove>
                    <div class="col-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>SKU</th>
                                    <th>Name</th>
                                    <th>
                                        Image
                                    </th>
                                    <th>Qty</th>
                                    <th>
                                        Price/Unit
                                    </th>
                                    <th>
                                        Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order?->items as $item)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td style="max-width: fit-content">
                                            {{ $item->variant_sku ?? '---' }}
                                        </td>
                                        <td style="max-width: 110px; text-wrap: pretty;">
                                            {{ $item->title ?? '---' }}
                                        </td>
                                        <td>
                                            @if ($item->variant_image)
                                                <img src="{{ $item->variant_image }}" alt="{{ $item->title }}"
                                                    class="img-thumbnail" style="width: 90px; height: 90px;">
                                            @else
                                                ---
                                            @endif
                                        </td>
                                        <td>
                                            {{ $item->quantity }}
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
                                    <td colspan="5"></td>
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
