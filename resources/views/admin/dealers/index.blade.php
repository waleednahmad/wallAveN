@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => 'Dealers',
        'links' => [
            [
                'name' => 'Dashboard',
                'url' => route('dashboard'),
            ],
            [
                'name' => 'Dealers',
                'url' => '#',
            ],
        ],
    ])
@endsection



@section('content')
    <div class="card">
        <div class="card-body">
            <livewire:tables.dealer-table />
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
                <livewire:dashboard.dealers.update-password-form />
            </div>
        </div>
    </div>

    {{-- Show Dealer --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="showDealer" aria-labelledby="showDealerLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="showDealerLabel">
                Dealer Details
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <livewire:dashboard.dealers.show-dealer />
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {

            // ============== Update Password Offcanvas ==============
            Livewire.on('openUpdatePasswordOffcanvas', (event) => {
                let offcanvas = new bootstrap.Offcanvas(document.getElementById('updatePassword'));
                offcanvas.show();
                let dealer = event[0];
                Livewire.dispatch('setDealer', dealer);
            });

            // ============== Close Update Password Offcanvas ==============
            Livewire.on('closeUpdatePasswordOffcanvas', (event) => {
                bootstrap.Offcanvas.getInstance(document.getElementById('updatePassword')).hide();
            });

            // ============== Open openShowOffcanvas ==============
            Livewire.on('openShowOffcanvas', (event) => {
                let offcanvas = new bootstrap.Offcanvas(document.getElementById('showDealer'));
                offcanvas.show();
                let dealer = event[0];
                Livewire.dispatch('setDealer', dealer);
            });
        });
    </script>
@endpush
