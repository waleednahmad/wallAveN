@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => 'Abounded Checkout',
        'links' => [
            [
                'name' => 'Dashboard',
                'url' => route('dashboard'),
            ],
            [
                'name' => 'Abounded Checkout',
                'url' => '#',
            ],
        ],
    ])
@endsection



@section('content')
    <div class="card">
        <div class="card-body">
            <livewire:tables.abounded-checkout-table />
        </div>
    </div>

    {{-- Preview Order Modal --}}
    <div class="modal fade" id="previewOrder" tabindex="-1" aria-labelledby="previewOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header position-relative">
                    <h1 class="modal-title fs-5" id="previewOrderLabel">
                        Abbounded Checkout
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <livewire:dashboard.abounded-checkout.preview-abounded-checkouts />
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
            // ============== Open Preview Modal ==============
            Livewire.on('previewAboundedCheckout', (event) => {
                new bootstrap.Modal(document.getElementById('previewOrder')).show();
                Livewire.dispatch('setDealer', event[0]);
            });
        });
    </script>
@endpush
