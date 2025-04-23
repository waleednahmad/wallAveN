<div class="dashboard-content-wrap style-2">
    <div class="dashboard-order-content">
        <div class="dashboard-order-table">
            <h4>All Order</h4>

            {{-- Form FIlteration --}}
            {{-- public $search = '';
            public $status = 'all';
            public $from_date = '';
            public $to_date = ''; --}}

            <div class="mb-2 card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" class="form-control" id="search" wire:model.live="search">
                                <span class="text-muted">Search by id / total /p.o.no</span>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" wire:model.live="status">
                                    @foreach ($this->statuses as $status)
                                        <option value="{{ $status }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="from_date" class="form-label">From Date</label>
                                <input type="date" class="form-control" id="from_date" wire:model.live="from_date">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="to_date" class="form-label">To Date</label>
                                <input type="date" class="form-control" id="to_date" wire:model.live="to_date">
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <table>
                <thead>
                    <tr>
                        <th>Order No</th>
                        <th>
                            P.O No
                        </th>
                        <th>Total</th>
                        <th>
                            Rep
                        </th>
                        <th>
                            Ordered At
                        </th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td data-label="Order Id">
                                <span class="table-text">
                                    {{ $order->id }}
                                </span>
                            </td>
                            <td data-label="P.O No">
                                <span class="table-text">
                                    {{ $order->po_number ?? '-' }}
                                </span>
                            </td>
                            <td data-label="Price">
                                <span class="table-text">
                                    ${{ number_format($order->total, 2) }}
                                </span>
                            </td>

                            <td>
                                <span class="table-text">
                                    @if ($order->representative_id)
                                        Yes
                                    @endif

                                </span>
                            </td>
                            <td>
                                <span class="table-text">
                                    {{ $order->created_at->format('m/d/Y') }}
                                </span>
                            </td>

                            <td data-label="Status">
                                @if ($order->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif ($order->status == 'processing')
                                    <span class="badge bg-primary">Processing</span>
                                @elseif ($order->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif ($order->status == 'declined')
                                    <span class="badge bg-danger">Declined</span>
                                @elseif ($order->status == 'canceled')
                                    <span class="badge bg-secondary">Canceled</span>
                                @endif
                            </td>
                            <td data-label="Certificate" style="font-size: 10px !important">

                                {{-- Preview --}}
                                <button class="px-2 py-1 primary-btn1 btn-hover"
                                    wire:click="showOrderDetails({{ $order->id }})">
                                    <i class="fas fa-eye"></i>
                                    <strong></strong>
                                </button>

                                {{-- Cancel Order --}}
                                @if ($order->status == 'pending')
                                    <button class="px-2 py-1 primary-btn2 btn-hover"
                                        wire:click="showConfirmCancelOrderModal({{ $order->id }})">
                                        <i class="fas fa-times"></i>
                                        <strong></strong>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No orders found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {!! $orders->links('customComponents.pagination') !!}
        </div>
    </div>
</div>
