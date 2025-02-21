@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => 'Product Types',
        'links' => [
            [
                'name' => 'Dashboard',
                'url' => route('dashboard'),
            ],
            [
                'name' => 'Product Types',
                'url' => '#',
            ],
        ],
    ])
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#createProductType"
                    aria-controls="createProductType">
                    Add new product type
                </button>
            </div>
        </div>
        <div class="card-body">
            <livewire:tables.product-type-table />
        </div>
    </div>

    {{-- Create Offacnvas --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="createProductType" aria-labelledby="createProductTypeLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="createProductTypeLabel">
                Add New Product Type
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <livewire:dashboard.product-types.create-product-type-form />
        </div>
    </div>

    {{-- Edit Offcanvas --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="editProductType" aria-labelledby="editProductTypeLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="editProductTypeLabel">
                Edit Product Type
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <livewire:dashboard.product-types.edit-product-type-form />
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            // ============== Close Create Offcanvas ==============
            Livewire.on('closeCreateForm', (event) => {
                bootstrap.Offcanvas.getInstance(document.getElementById('createProductType')).hide();
            });

            // ============== Edit Offcanvas ==============
            Livewire.on('openEditOffcanvas', (event) => {
                let offcanvas = new bootstrap.Offcanvas(document.getElementById('editProductType'));
                offcanvas.show();
                let productType = event[0];
                Livewire.dispatch('editProductType',
                    productType
                );
            });

            // ============== CLose Edit Offcanvas ==============
            Livewire.on('closeEditForm', (event) => {
                bootstrap.Offcanvas.getInstance(document.getElementById('editProductType')).hide();
            });
        });
    </script>
@endpush
