@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => 'Page Breadcrumps',
        'links' => [
            [
                'name' => 'Dashboard',
                'url' => route('dashboard'),
            ],
            [
                'name' => 'Page Breadcrumps',
                'url' => '#',
            ],
        ],
    ])
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <livewire:tables.page-breadcrump-table />
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editBreadcrumpModal" tabindex="-1" aria-labelledby="editBreadcrumpModalLabel"
        aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editBreadcrumpModalLabel">
                        Edit Breadcrump
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <livewire:dashboard.breadcrumps.edit-modal />
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            // ============== Open Edit Modal ==============
            Livewire.on('openEditPageModal', (event) => {
                new bootstrap.Modal(document.getElementById('editBreadcrumpModal')).show();
                let pageBreadcrump = event[0];
                Livewire.dispatch('setBreadcrump', pageBreadcrump);
            });

            // ============== Close Edit Modal ==============
            Livewire.on('closeEditModal', (event) => {
                bootstrap.Modal.getInstance(document.getElementById('editBreadcrumpModal')).hide();
            });

        });
    </script>
@endpush
