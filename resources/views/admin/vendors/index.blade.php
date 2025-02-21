@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => 'Vendors',
        'links' => [
            [
                'name' => 'Dashboard',
                'url' => route('dashboard'),
            ],
            [
                'name' => 'Vendors',
                'url' => '#',
            ],
        ],
    ])
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#createVendor"
                    aria-controls="createVendor">
                    Add new vendor
                </button>
            </div>
        </div>
        <div class="card-body">
            <livewire:tables.vendor-table />
        </div>
    </div>

    {{-- Create Offacnvas --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="createVendor" aria-labelledby="createVendorLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="createVendorLabel">
                Add New Vendor
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <livewire:dashboard.vendors.create-vendor-offcanva />
        </div>
    </div>

    {{-- Edit Offcanvas --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="editVendor" aria-labelledby="editVendorLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="editVendorLabel">
                Edit Vendor
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <livewire:dashboard.vendors.edit-vendor-offcanva />
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {

            // ============== Close Create Vendor Offcanvas ==============
            Livewire.on('closeCreateOffcanvas', (event) => {
                bootstrap.Offcanvas.getInstance(document.getElementById('createVendor')).hide();
            });

            // ============= Open Edit Vendor Offcanvas ==============
            Livewire.on('openEditOffcanvas', (event) => {
                let offcanvas = new bootstrap.Offcanvas(document.getElementById('editVendor'));
                offcanvas.show();
                let vendor = event[0];
                Livewire.dispatch('editVendor', vendor);
            });
            // ============== Close Edit Vendor Offcanvas ==============
            Livewire.on('closeEditOffcanvas', (event) => {
                bootstrap.Offcanvas.getInstance(document.getElementById('editVendor')).hide();
            });
        });
    </script>
@endpush
