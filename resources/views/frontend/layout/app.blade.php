<!doctype html>
<html lang="en">

<head>
    @include('frontend.layout.partials.head')
    @stack('styles')
    @vite(['resources/front/css/app.css', 'resources/front/js/app.js'])
</head>

<body id="body">

    <div class="tt-style-switch d-lg-flex d-none">
        <span class="dark">Dark</span>
        <span class="light">Light</span>
    </div>

    <!-- scroll top start -->
    <div class="circle-container">
        <svg class="circle-progress svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"
                style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919; stroke-dashoffset: 256.939;">
            </path>
        </svg>
    </div>
    <!-- scroll top end -->

    <!-- Header Start -->
    @include('frontend.layout.partials.header')
    <!-- Header End -->

    @yield('content')


    <!-- Home1 Footer Section Start -->
    @include('frontend.layout.partials.footer')
    <!-- Home1 Footer Section End -->

    {{-- ================ OFFCANVAS ================= --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanva" aria-labelledby="cartOffcanva">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="cartOffcanva">
                Your Cart
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <livewire:frontend.cart-offcanva />
        </div>
    </div>


    {{-- =============== MODALS =============== --}}
    <div class="modal fade" id="ConfirmCheckout" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="ConfirmCheckoutLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <livewire:frontend.modals.conform-checkout-modal />
            </div>
        </div>
    </div>

    @auth('representative')
        <div class="modal fade" id="DealerSelection" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="DealerSelectionLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">
                            Please select a dealer to proceed ...
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <livewire:frontend.modals.dealer-selection-modal />
                </div>
            </div>
        </div>
    @endauth






    @include('frontend.layout.partials.scripts')
    @stack('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            // ============== Edit Offcanvas ==============
            Livewire.on('openCartOffcanva', (event) => {
                let offcanvas = new bootstrap.Offcanvas(document.getElementById('cartOffcanva'));
                offcanvas.show();
                // Livewire.dispatch('mount', $client);
            });

            // ============== Close Offcanvas ==============
            Livewire.on('closeCartOffcanva', (event) => {
                bootstrap.Offcanvas.getInstance(document.getElementById('cartOffcanva')).hide();
            });

            // ============== Open Confirm Checkout Modal ==============
            Livewire.on('openConfirmCheckoutModal', (event) => {
                let modal = new bootstrap.Modal(document.getElementById('ConfirmCheckout'));
                modal.show();
            });

            // ============== Close Confirm Checkout Modal =================
            Livewire.on('closeConfirmCheckoutModal', (event) => {
                bootstrap.Modal.getInstance(document.getElementById('ConfirmCheckout')).hide();
            });

            // ============== Open Dealer Selection Modal ==============
            Livewire.on('openDealerSelectionModal', (event) => {
                let modal = new bootstrap.Modal(document.getElementById('DealerSelection'));
                modal.show();
            });

            // ============== Close Dealer Selection Modal =================
            Livewire.on('closeDealerSelectionModal', (event) => {
                bootstrap.Modal.getInstance(document.getElementById('DealerSelection')).hide();
            });
        });
    </script>
</body>

</html>
