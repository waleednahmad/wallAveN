@extends('frontend.layout.app')

@section('content')
    <div class="dashboard-section mb-120">
        <div class="container">
            <div class="dashboard-wrapper">
                <livewire:frontend.dealer.dashboard.sidebar-component />
                <livewire:frontend.dealer.dashboard.orders-page />
            </div>
        </div>
    </div>

    {{-- Modals --}}
    {{-- Preview Order Modal --}}
    <div class="modal fade" id="previewOrder" tabindex="-1" aria-labelledby="previewOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header position-relative">
                    <h1 class="modal-title fs-5" id="previewOrderLabel">
                        Order Details
                    </h1>

                    <button type="button" class="btn btn-primary me-2 float-end position-absolute" style="right: 45px"
                        onclick="openPrintOrder()">
                        <i class="bi bi-printer"></i>
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <livewire:frontend.dealer.dashboard.preview-order-modal />
            </div>
        </div>
    </div>

    {{-- Confirm Cancel Order Modal --}}

    <div class="modal fade" id="confirmCancelOrder" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="confirmCancelOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmCancelOrderLabel">Confirm Cancel Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <livewire:frontend.dealer.dashboard.confirm-cancel-order-modal />
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        let currentOrderId = null;

        function openPrintOrder() {
            if (currentOrderId) {
                window.open(`orders/${currentOrderId}/print`, '_blank');
            }
        }


        document.addEventListener('livewire:init', () => {
            // ============== Open Preview Modal ==============
            Livewire.on('showOrderDetails', (event) => {
                new bootstrap.Modal(document.getElementById('previewOrder')).show();
                Livewire.dispatch('setOrder', event[0]);
                currentOrderId = event[0].order; // Store the current order ID

            });

            // ============== showConfirmCancelOrderModal ==============
            Livewire.on('showConfirmCancelOrder', (event) => {
                new bootstrap.Modal(document.getElementById('confirmCancelOrder')).show();
                Livewire.dispatch('setOrderId', event[0]);
            });

            // ============== closeConfirmModal ==============
            Livewire.on('closeConfirmModal', () => {
                bootstrap.Modal.getInstance(document.getElementById('confirmCancelOrder')).hide();
            });
        });
    </script>
@endpush
