@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => 'Price Lists',
        'links' => [
            [
                'name' => 'Dashboard',
                'url' => route('dashboard'),
            ],
            [
                'name' => 'Price Lists',
                'url' => '#',
            ],
        ],
    ])
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#createPriceList"
                    aria-controls="createPriceList">
                    Add new price list
                </button>
            </div>
        </div>
        <div class="card-body">
            <livewire:tables.price-list-table />
        </div>
    </div>

    {{-- Create Offcanvas --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="createPriceList" aria-labelledby="createPriceListLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="createPriceListLabel">
                Add New Price List
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <livewire:dashboard.price-lists.create-price-list-form />
        </div>
    </div>

    {{-- Edit Offcanvas --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="editPriceList" aria-labelledby="editPriceListLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="editPriceListLabel">
                Edit Price List
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <livewire:dashboard.price-lists.edit-price-list-form />
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            // ============== Edit Offcanvas ==============
            Livewire.on('openEditOffcanvas', (event) => {
                let offcanvas = new bootstrap.Offcanvas(document.getElementById('editPriceList'));
                offcanvas.show();
                let priceList = event[0];
                Livewire.dispatch('editPriceList',
                    priceList
                );
            });

            // ============== Close Edit Offcanvas ==============
            Livewire.on('closeEditForm', (event) => {
                bootstrap.Offcanvas.getInstance(document.getElementById('editPriceList')).hide();
            });

            // ============== Close Create Offcanvas ==============
            Livewire.on('closeCreateForm', (event) => {
                bootstrap.Offcanvas.getInstance(document.getElementById('createPriceList')).hide();
            });
        });
    </script>
@endpush
