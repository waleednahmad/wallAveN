@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => 'Representatives',
        'links' => [
            [
                'name' => 'Dashboard',
                'url' => route('dashboard'),
            ],
            [
                'name' => 'Representatives',
                'url' => '#',
            ],
        ],
    ])
@endsection



@section('content')
    <div class="card">
        <div class="card-body">
            <livewire:tables.representative-table />
        </div>
    </div>


    {{-- Update Password --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="updatePassword" aria-labelledby="updatePasswordLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="updatePasswordLabel">
                Update Password
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="card">
                <livewire:dashboard.representatives.update-password-form />
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {

            // ============== Update Password Offcanvas ==============
            Livewire.on('openUpdatePasswordOffcanvas', (event) => {
                new bootstrap.Offcanvas(document.getElementById('updatePassword')).show();
                let representative = event[0];
                Livewire.dispatch('setRepresentative', representative);
            });


            // ============== Close Update Password Offcanvas ==============
            Livewire.on('closeUpdatePasswordOffcanvas', (event) => {
                bootstrap.Offcanvas.getInstance(document.getElementById('updatePassword')).hide();
            });
        });

    </script>
@endpush
