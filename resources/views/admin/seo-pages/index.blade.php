@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => 'SEO Pages',
        'links' => [
            [
                'name' => 'Dashboard',
                'url' => route('dashboard'),
            ],
            [
                'name' => 'SEO Pages',
                'url' => '#',
            ],
        ],
    ])
@endsection



@section('content')
    <div class="card">
        <div class="card-body">
            <livewire:tables.seo-page-table/>
        </div>
    </div>


    {{-- ========== Edit Offcanva ========= --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="editSeoPage" aria-labelledby="editSeoPageLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="editSeoPageLabel">
                Edit Seo Pages
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <livewire:dashboard.seo-pages.edit-seo-page-modal />
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {

            // ============== Open Edit Offcanvas ==============
            Livewire.on('openEditSeoPageModal', (event) => {
                let offcanvas = new bootstrap.Offcanvas(document.getElementById('editSeoPage'));
                offcanvas.show();
                let page = event[0];
                Livewire.dispatch('setPage', page);
            });

            // ============== Close Edit Offcanvas ==============
            Livewire.on('closeModal', () => {
                bootstrap.Offcanvas.getInstance(document.getElementById('editSeoPage')).hide();
            });
        });
    </script>
@endpush
