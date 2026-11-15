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
    @php
        $grTab = in_array(request('tab'), ['applications', 'approved', 'inactive', 'declined'], true) ? request('tab') : 'all';
        $grCounts = [
            'all' => \App\Models\Dealer::count(),
            'applications' => \App\Models\Dealer::pendingApplication()->count(),
            'approved' => \App\Models\Dealer::where('is_approved', true)->count(),
            'inactive' => \App\Models\Dealer::where('status', false)->count(),
            'declined' => \App\Models\Dealer::whereNotNull('rejected_at')->count(),
        ];
        $grLabels = ['all' => 'All', 'applications' => 'Applications', 'approved' => 'Approved', 'inactive' => 'Inactive', 'declined' => 'Declined'];
    @endphp

    {{-- Tabs: counts move live when an application is approved or declined --}}
    <nav class="adm-tabs" aria-label="Dealer status"
        x-data="{ c: @js($grCounts) }"
        x-on:dealer-applications-changed.window="c.applications = Math.max(0, c.applications - 1); c[$event.detail.result] = (c[$event.detail.result] || 0) + 1">
        @foreach ($grLabels as $grKey => $grLabel)
            <a href="{{ route('dashboard.dealers.index', $grKey === 'all' ? [] : ['tab' => $grKey]) }}"
                @class(['adm-tab', 'is-on' => $grTab === $grKey]) @if ($grTab === $grKey) aria-current="page" @endif>
                {{ $grLabel }} <span class="adm-tab-n" x-text="c.{{ $grKey }}">{{ number_format($grCounts[$grKey]) }}</span>
            </a>
        @endforeach
    </nav>

    @if ($grTab === 'applications')
        <livewire:dashboard.dealers.applications />
    @else
        <div class="card">
            <div class="card-body">
                <livewire:tables.dealer-table />
            </div>
        </div>
    @endif

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

    {{-- SetPrice List --}}
    {{-- priceListMoodalModal --}}
    <div class="modal fade" id="priceListMoodal" tabindex="-1" aria-labelledby="priceListMoodalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="priceListMoodalLabel">
                        Set Price List For Dealers
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <livewire:dashboard.dealers.set-price-list-modal />
            </div>
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
                bootstrap.Offcanvas.getOrCreateInstance(document.getElementById('updatePassword')).hide();
            });

            // ============== Deep link: /super_admin/dealers?dealer=12 opens the dealer details ==============
            document.addEventListener('livewire:initialized', () => {
                const dealerId = parseInt(new URLSearchParams(location.search).get('dealer'), 10);
                if (dealerId) {
                    new bootstrap.Offcanvas(document.getElementById('showDealer')).show();
                    Livewire.dispatch('setDealer', { dealer: { id: dealerId } });
                }
            });

            // ============== Open openShowOffcanvas ==============
            Livewire.on('openShowOffcanvas', (event) => {
                let offcanvas = new bootstrap.Offcanvas(document.getElementById('showDealer'));
                offcanvas.show();
                let dealer = event[0];
                Livewire.dispatch('setDealer', dealer);
            });

            // ============== Open Accept Order Modal ==============
            Livewire.on('openPriceListModal', (event) => {
                let modal = new bootstrap.Modal(document.getElementById('priceListMoodal'));
                modal.show();

                let dealers = event.dealers ? Array.from(event.dealers) : [];
                Livewire.dispatch('setDealers', {
                    dealers
                });
            });

            // ============== Open Send Order MOdal Bulk ==============

            // ============== Close Accept Order Modal ==============
            Livewire.on('closePriceListMoodal', (event) => {
                bootstrap.Modal.getOrCreateInstance(document.getElementById('priceListMoodal')).hide();
            });
        });
    </script>
@endpush
