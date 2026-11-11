<!--  Main jQuery  -->
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<!-- Popper and Bootstrap JS -->
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<!-- Swiper slider JS -->
<script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/slick.min.js') }}"></script>
<!-- Waypoints JS -->
<script src="{{ asset('assets/js/waypoints.min.js') }}"></script>
<!-- Counterup JS -->
<script src="{{ asset('assets/js/jquery.counterup.min.js') }}"></script>
<!-- Nice Select  JS -->
<script src="{{ asset('assets/js/jquery.nice-select.min.js') }}"></script>
<!-- Fancybox  JS -->
<script src="{{ asset('assets/js/jquery.fancybox.min.js') }}"></script>
<!-- Wow  JS -->
<script src="{{ asset('assets/js/wow.min.js') }}"></script>
<!-- Marquee  JS -->
<script src="{{ asset('assets/js/jquery.marquee.min.js') }}"></script>

<script src="{{ asset('assets/js/main.js') }}?v={{ filemtime(public_path('assets/js/main.js')) }}"></script>


@vite('resources/front/js/app.js')
<script src="{{ asset('assets/js/gr-toast.js') }}?v={{ filemtime(public_path('assets/js/gr-toast.js')) }}"></script>
<script>
    // Messages: page-load flashes and Livewire events all use the storefront toast (gr-toast.js).
    document.addEventListener('DOMContentLoaded', function() {
        @if (session()->has('success'))
            grToast('success', @js(session()->get('success')));
        @endif
        @if (session()->has('error'))
            grToast('error', @js(session()->get('error')));
        @endif
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                grToast('error', @js($error));
            @endforeach
        @endif
    });

    document.addEventListener('livewire:init', () => {
        ['success', 'error', 'info', 'warning'].forEach((type) => {
            Livewire.on(type, (event) => grToast(type, event));
        });
        Livewire.on('validationFailed', (event) => {
            (Array.isArray(event) ? (event[0] || []) : []).forEach((error) => grToast('error', error));
        });
        // Added to cart (quick add and product page)
        Livewire.on('cart-added', (event) => {
            grToast('success', event, { action: { label: 'View cart', onClick: grOpenCart } });
        });
    });

</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuItems = document.querySelectorAll('header.style-1 .main-menu ul > li.menu-item-has-children');

        menuItems.forEach(item => {
            const subMenu = item.querySelector('ul.sub-menu');
            if (subMenu && subMenu.children.length === 0) { // Check if sub-menu is empty
                item.classList.add('sub-menu-empty');
            }
        });
    });
</script>
