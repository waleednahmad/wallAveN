@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => 'Product Variants',
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
                'name' => 'Create Variant',
            ],
        ],
    ])
@endsection



@section('content')
    <livewire:dashboard.products.product-variants-page :product="$product" />

    {{-- Offcanvas --}}
    <div class="offcanvas offcanvas-end" id="createVariant" aria-labelledby="createVariantLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="createVariantLabel">
                Add New Variant
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <livewire:dashboard.products.create-product-variant-form :product="$product" />
        </div>
    </div>

    <div class="offcanvas offcanvas-end" id="editVariant" aria-labelledby="editVariantLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="editVariantLabel">
                Edit Variant
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <livewire:dashboard.products.edit-product-variant-form :product="$product" />
        </div>
    </div>

    {{-- Variant Attribut Values --}}
    <div class="offcanvas offcanvas-end" id="variantAttributes" aria-labelledby="variantAttributesLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="variantAttributesLabel">
                Edit Variant Attributes
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <livewire:dashboard.products.edit-variant-attribute-values-form :product="$product" />
        </div>
    </div>

    {{-- Preview Delete Modal --}}
    <div class="modal fade" id="confirmDeleteVariant" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="confirmDeleteVariantLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteVariantLabel">
                        Confirm Delete Variant
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <livewire:dashboard.product-variants.confirm-delete-modal />
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            // ============== Open Offcanvas ==============
            Livewire.on('openCreateVariantOffcanvas', (event) => {
                let offcanvas = new bootstrap.Offcanvas(document.getElementById('createVariant'));
                offcanvas.show();
            });

            // ============== Close Offcanvas ==============
            Livewire.on('closeCreateVariantOffcanvas', (event) => {
                bootstrap.Offcanvas.getInstance(document.getElementById('createVariant')).hide();
            });

            // ============== Open Offcanvas ==============
            Livewire.on('openEditVariantAttributesOffcanvas', (event) => {
                let offcanvas = new bootstrap.Offcanvas(document.getElementById('variantAttributes'));
                offcanvas.show();

                let variant = event[0]
                Livewire.dispatch('setVariant', variant);
            });

            // ============== Close Offcanvas ==============
            Livewire.on('closeEditVariantAttributesOffcanvas', (event) => {
                bootstrap.Offcanvas.getInstance(document.getElementById('variantAttributes')).hide();
            });


            // ============= Open Edit Offcanvas ==============
            Livewire.on('openEditVariantOffcanvas', (event) => {
                let offcanvas = new bootstrap.Offcanvas(document.getElementById('editVariant'));
                offcanvas.show();

                let variant = event[0]
                Livewire.dispatch('setVariant', variant);
            });

            // ============= Close Edit Offcanvas ==============
            Livewire.on('closeEditVariantOffcanvas', (event) => {
                bootstrap.Offcanvas.getInstance(document.getElementById('editVariant')).hide();
            });

            // ============== showConfirmDeleteVariantModal ==============
            Livewire.on('confirmDeleteVariant', (event) => {
                new bootstrap.Modal(document.getElementById('confirmDeleteVariant')).show();
                console.log(event)
                Livewire.dispatch('setVariant', event[0]);
            });
        });

        // Remove the whitespace from the sku input field while typing like "afadfh  123" to "afadfh123"
        $(document).ready(function() {
            $('#sku').on('input', function() {
                this.value = this.value.replace(/\s/g, '');
            });
        });
    </script>
@endpush
