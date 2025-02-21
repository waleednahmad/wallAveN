@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => 'Public Settings',
        'links' => [
            [
                'name' => 'Dashboard',
                'url' => route('dashboard'),
            ],
            [
                'name' => 'Public Settings',
                'url' => '#',
            ],
        ],
    ])
@endsection



@section('content')
    <div class="card">
        <div class="card-body">
            <livewire:tables.public-setting-table />
        </div>
    </div>


    {{-- ========== Edit Offcanva ========= --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="editPublicSetting" aria-labelledby="editPublicSettingLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="editPublicSettingLabel">
                Edit Public Setting
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <livewire:dashboard.public-settings.edit-public-setting />
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {

            // ============== Open Edit Offcanvas ==============
            Livewire.on('openEditOffcanvas', (event) => {
                let offcanvas = new bootstrap.Offcanvas(document.getElementById('editPublicSetting'));
                offcanvas.show();
                let publicSetting = event[0];
                console.log(publicSetting);
                Livewire.dispatch('setPublicSetting', publicSetting);
            });

            // ============== Close Edit Offcanvas ==============
            Livewire.on('closeEditOffcanvas', () => {
                bootstrap.Offcanvas.getInstance(document.getElementById('editPublicSetting')).hide();
            });
        });
    </script>
@endpush
