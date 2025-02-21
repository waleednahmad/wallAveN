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

    {{-- Preview Order Modal --}}
    <div class="modal fade" id="previewOrder" tabindex="-1" aria-labelledby="previewOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="previewOrderLabel">
                        Order Details
                    </h1>
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
        document.addEventListener('livewire:init', () => {
            // ============== Open Preview Modal ==============
            Livewire.on('showOrderDetails', (event) => {
                new bootstrap.Modal(document.getElementById('previewOrder')).show();
                Livewire.dispatch('setOrder', event[0]);
            });

            // ============== Open Change Status MOdal ==============
            Livewire.on('showChangeStatusModal', (event) => {
                new bootstrap.Modal(document.getElementById('changeStatus')).show();
                Livewire.dispatch('setOrder', event[0]);
            });

            // ============== closeChangeStatusModal  ==============
            Livewire.on('closeChangeStatusModal', () => {
                bootstrap.Modal.getInstance(document.getElementById('changeStatus')).hide();
            });
        });
    </script>
@endpush
