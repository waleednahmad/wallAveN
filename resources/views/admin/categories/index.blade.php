@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => 'Categories',
        'links' => [
            [
                'name' => 'Dashboard',
                'url' => route('dashboard'),
            ],
            [
                'name' => 'Categories',
                'url' => '#',
            ],
        ],
    ])
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#createCategory"
                    aria-controls="createCategory">
                    Add new category
                </button>
            </div>
        </div>
        <div class="card-body">
            <livewire:tables.category-table />
        </div>
    </div>

    {{-- Create Offacnvas --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="createCategory" aria-labelledby="createCategoryLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="createCategoryLabel">
                Add New Category
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <livewire:dashboard.categories.create-category-form />
        </div>
    </div>

    {{-- Edit Offcanvas --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="editCategory" aria-labelledby="editCategoryLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="editCategoryLabel">
                Edit Category
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <livewire:dashboard.categories.edit-category-form />
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            // ============== Edit Offcanvas ==============
            Livewire.on('openEditOffcanvas', (event) => {
                let offcanvas = new bootstrap.Offcanvas(document.getElementById('editCategory'));
                offcanvas.show();
                let category = event[0];
                Livewire.dispatch('editCategory',
                    category
                );
            });

            // ============== CLose Edit Offcanvas ==============
            Livewire.on('closeEditForm', (event) => {
                bootstrap.Offcanvas.getInstance(document.getElementById('editCategory')).hide();
            });

            // ============== Close Create Offcanvas ==============
            Livewire.on('closeCreateForm', (event) => {
                bootstrap.Offcanvas.getInstance(document.getElementById('createCategory')).hide();
            });
        });
    </script>
@endpush
