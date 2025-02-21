@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => 'Sub-Categories',
        'links' => [
            [
                'name' => 'Dashboard',
                'url' => route('dashboard'),
            ],
            [
                'name' => 'Sub-Categories',
                'url' => '#',
            ],
        ],
    ])
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#createSubCategory"
                    aria-controls="createSubCategory">
                    Add new sub-category
                </button>
            </div>
        </div>
        <div class="card-body">
            <livewire:tables.sub-category-table />
        </div>
    </div>

    {{-- Create Offacnvas --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="createSubCategory" aria-labelledby="createSubCategoryLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="createSubCategoryLabel">
                Add New Sub-Category
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <livewire:dashboard.sub-categories.create-sub-category-form />
        </div>
    </div>

    {{-- Edit Offcanvas --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="editSubCategory" aria-labelledby="editSubCategoryLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="editSubCategoryLabel">
                Edit Sub-Category
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <livewire:dashboard.sub-categories.edit-sub-category-form />
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            // ============== Close Create Offcanvas ==============
            Livewire.on('closeCreateForm', (event) => {
                bootstrap.Offcanvas.getInstance(document.getElementById('createSubCategory')).hide();
            });

            // ============== Edit Offcanvas ==============
            Livewire.on('openEditOffcanvas', (event) => {
                let offcanvas = new bootstrap.Offcanvas(document.getElementById('editSubCategory'));
                offcanvas.show();
                let subCategory = event[0];
                Livewire.dispatch('editSubCategory',
                    subCategory
                );
            });

            // ============== CLose Edit Offcanvas ==============
            Livewire.on('closeEditOffcanvas', (event) => {
                bootstrap.Offcanvas.getInstance(document.getElementById('editSubCategory')).hide();
            });

        });
    </script>
@endpush
