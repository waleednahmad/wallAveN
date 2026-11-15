@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => 'Orders',
        'links' => [
            [
                'name' => 'Dashboard',
                'url' => route('dashboard'),
            ],
            [
                'name' => 'Orders',
                'url' => '#',
            ],
        ],
    ])
@endsection



@section('content')
    <div class="card">
        <div class="card-body">
            <livewire:tables.order-table />
        </div>
    </div>

    {{-- Order side panel: details, status steps, items, print/PDF (App\Livewire\Dashboard\Orders\OrderDrawer) --}}
    <div class="offcanvas offcanvas-end adm-drawer-panel" tabindex="-1" id="orderDrawer" aria-label="Order details">
        <button type="button" class="btn-close adm-drawer-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        <livewire:dashboard.orders.order-drawer />
    </div>

    {{-- Preview Order Modal (kept; the table now opens the side panel) --}}
    <div class="modal fade" id="previewOrder" tabindex="-1" aria-labelledby="previewOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header position-relative">
                    <h1 class="modal-title fs-5" id="previewOrderLabel">
                        Order Details
                    </h1>

                    <button type="button" class="btn btn-primary me-2 float-end position-absolute" style="right: 45px"
                        onclick="openPrintOrder()">
                        <i class="fas fa-print"></i>
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <livewire:dashboard.orders.preview-order-modal />
            </div>
        </div>
    </div>

    {{-- Change Status Modal --}}
    <div class="modal fade" id="changeStatus" tabindex="-1" aria-labelledby="changeStatusLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="changeStatusLabel">
                        Change Order Status
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <livewire:dashboard.orders.change-status-modal />
            </div>
        </div>
    </div>
@endsection



@push('scripts')
    <script>
        function printOrderDetails() {
            const printContents = document.querySelector('#previewOrder .invoice').innerHTML;
            const originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload(); // To restore event listeners and scripts
        }

        let currentOrderId = null;

        function openPrintOrder() {
            if (currentOrderId) {
                window.open(`/super_admin/orders/${currentOrderId}/print`, '_blank');
            }
        }

        function printOrderFromTable(orderId) {
            console.log('Printing order with ID:', orderId);
            // Open the preview modal and set the order, then print after a short delay
            Livewire.dispatch('showOrderDetails', [{
                'order': orderId
            }]);
            Livewire.dispatch('setOrder', orderId);

            setTimeout(() => {
                printOrderDetails();
            }, 2000); // Adjust delay if needed for modal content to load
        }

        document.addEventListener('livewire:init', () => {
            // ============== Order side panel ==============
            Livewire.on('showOrderDrawer', () => {
                bootstrap.Offcanvas.getOrCreateInstance(document.getElementById('orderDrawer')).show();
            });
        });

        // Deep link from the global search: /super_admin/orders?order=403 opens that order's panel
        document.addEventListener('livewire:initialized', () => {
            const orderId = parseInt(new URLSearchParams(location.search).get('order'), 10);
            if (orderId) {
                Livewire.dispatch('openOrderDrawer', { orderId });
            }

            // ============== Open Preview Modal ==============
            Livewire.on('showOrderDetails', (event) => {
                new bootstrap.Modal(document.getElementById('previewOrder')).show();
                Livewire.dispatch('setOrder', event[0]);
                currentOrderId = event[0].order; // Store the current order ID
            });

            // ============== Open Change Status MOdal ==============
            Livewire.on('showChangeStatusModal', (event) => {
                new bootstrap.Modal(document.getElementById('changeStatus')).show();
                Livewire.dispatch('setOrder', event[0]);
            });

            // ============== closeChangeStatusModal  ==============
            Livewire.on('closeChangeStatusModal', () => {
                bootstrap.Modal.getOrCreateInstance(document.getElementById('changeStatus')).hide();
            });
        });
    </script>
@endpush
