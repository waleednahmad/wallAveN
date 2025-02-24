@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => 'Attributes',
        'links' => [
            [
                'name' => 'Dashboard',
                'url' => route('dashboard'),
            ],
            [
                'name' => 'Attributes',
                'url' => '#',
            ],
        ],
    ])
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAttributeModal">
                    Add new Attribute
                </button>

            </div>
        </div>
        <div class="card-body">
            <livewire:tables.attribute-table />
        </div>
    </div>


    {{-- Create Modal --}}
    <div class="modal fade" id="createAttributeModal" tabindex="-1" aria-labelledby="createAttributeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createAttributeModalLabel">
                        Create Attribute
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <livewire:dashboard.attributes.create-attribute-modal />
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editAttributeModal" tabindex="-1" aria-labelledby="editAttributeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editAttributeModalLabel">
                        Edit Attribute
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <livewire:dashboard.attributes.edit-attribute-modal />
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            // ============== Close Create Modal ==============
            Livewire.on('closeCreateModal', (event) => {
                bootstrap.Modal.getInstance(document.getElementById('createAttributeModal')).hide();
            });

            // ============== Open Edit Modal ==============
            Livewire.on('openEditModal', (event) => {
                new bootstrap.Modal(document.getElementById('editAttributeModal')).show();
                let attribute = event[0];
                Livewire.dispatch('setEditAttribute', attribute);
            });

            // ============== Close Edit Modal ==============
            Livewire.on('closeEditModal', (event) => {
                bootstrap.Modal.getInstance(document.getElementById('editAttributeModal')).hide();
            });

        });
    </script>
@endpush
