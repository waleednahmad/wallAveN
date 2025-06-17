@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => 'Products',
        'links' => [
            [
                'name' => 'Dashboard',
                'url' => route('dashboard'),
            ],
            [
                'name' => 'Products',
                'url' => route('dashboard.products.index'),
            ],
            [
                'name' => 'Create',
            ],
        ],
    ])
@endsection



@section('content')
    <livewire:dashboard.products.create-product-form />

    {{-- Create Category Ofcanvas --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="addNewCategory" aria-labelledby="addNewCategoryLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="addNewCategoryLabel">
                Add New Category
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <livewire:dashboard.products.offcanvas.categories.create-category-offcanva />
        </div>
    </div>
    {{-- Create SubCategory Ofcanvas --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="addNewSubCategory" aria-labelledby="addNewSubCategoryLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="addNewSubCategoryLabel">
                Add New Sub Category
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <livewire:dashboard.products.offcanvas.sub-categories.create-sub-category-offcanva />
        </div>
    </div>

    {{-- Create ProductType Ofcanvas --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="addNewProductType" aria-labelledby="addNewProductTypeLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="addNewProductTypeLabel">
                Add New Sub Category
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <livewire:dashboard.products.offcanvas.product-types.create-product-type-offcanva />
        </div>
    </div>
@endsection



@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            // ============== Open Create Catregory Offcanvas ==============
            Livewire.on('openAddCategoryOffcanvas', (event) => {
                let offcanvas = new bootstrap.Offcanvas(document.getElementById('addNewCategory'));
                offcanvas.show();
                Livewire.dispatch('setProductFiles', event[0]);
            });

            // ============== CLose Create Category Offcanvas ==============
            Livewire.on('closeCreateCategoryForm', (event) => {
                bootstrap.Offcanvas.getInstance(document.getElementById('addNewCategory')).hide();
            });

            // ============== Open Create SubCatregory Offcanvas ==============
            Livewire.on('openAddSubCategoryOffcanvas', (event) => {
                let offcanvas = new bootstrap.Offcanvas(document.getElementById('addNewSubCategory'));
                offcanvas.show();
                Livewire.dispatch('setProductFiles', event[0]);
            });

            // ============== CLose Create SubCategory Offcanvas ==============
            Livewire.on('closeCreateSubCategoryForm', (event) => {
                bootstrap.Offcanvas.getInstance(document.getElementById('addNewSubCategory')).hide();
            });

            // ============== Open Create ProductType Offcanvas ==============
            Livewire.on('openAddProductTypeOffcanvas', (event) => {
                let offcanvas = new bootstrap.Offcanvas(document.getElementById('addNewProductType'));
                offcanvas.show();
                Livewire.dispatch('setProductFiles', event[0]);
            });

            // ============== CLose Create ProductType Offcanvas ==============
            Livewire.on('closeCreateProductTypeForm', (event) => {
                bootstrap.Offcanvas.getInstance(document.getElementById('addNewProductType')).hide();
            });
        });
    </script>
@endpush
