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

<script src="{{ asset('assets/js/main.js') }}"></script>


@vite('resources/front/js/app.js')
<script>
    // Wait for Vite bundle to load before using Swal
    document.addEventListener('DOMContentLoaded', function() {
        // Function to wait for Swal to be available
        function waitForSwal(callback) {
            if (typeof window.Swal !== 'undefined') {
                callback();
            } else {
                setTimeout(() => waitForSwal(callback), 10);
            }
        }

        // Initialize SweetAlert2 messages after Swal is available
        waitForSwal(function() {
            // ========== SweetAlert2 Messages ==========
            // success messages
            @if (session()->has('success'))
                Swal.fire({
                    icon: 'success',
                    title: '{{ session()->get('success') }}',
                    timer: 6000,
                    showConfirmButton: false,
                    // toast: true,
                    position: 'top-right'
                });
            @endif
            // error messages
            @if (session()->has('error'))
                Swal.fire({
                    icon: 'error',
                    title: '{{ session()->get('error') }}',
                    timer: 6000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-right'
                });
            @endif

            // error validation messages
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    Swal.fire({
                        icon: 'error',
                        title: '{{ $error }}',
                        timer: 6000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-right'
                    });
                @endforeach
            @endif
            // ========== End SweetAlert2 Messages ==========
        });
    });

    document.addEventListener('livewire:init', () => {
        // Ensure Swal is available for Livewire events too
        function waitForSwal(callback) {
            if (typeof window.Swal !== 'undefined') {
                callback();
            } else {
                setTimeout(() => waitForSwal(callback), 10);
            }
        }

        waitForSwal(function() {
            // Success Message
            Livewire.on('success', (event) => {
                Swal.fire({
                    icon: 'success',
                    title: event[0],
                    timer: 6000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-right'
                });
            });
            // Error Message
            Livewire.on('error', (event) => {
                Swal.fire({
                    icon: 'error',
                    title: event[0],
                    timer: 6000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-right'
                });
            });
            // Info Message
            Livewire.on('info', (event) => {
                Swal.fire({
                    icon: 'info',
                    title: event[0],
                    timer: 6000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-right'
                });
            });
            // Warning Message
            Livewire.on('warning', (event) => {
                Swal.fire({
                    icon: 'warning',
                    title: event[0],
                    timer: 6000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-right'
                });
            });

            // Error Validation Message
            Livewire.on('validationFailed', (event) => {
                event[0].forEach((error) => {
                    Swal.fire({
                        icon: 'error',
                        title: error,
                        timer: 6000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-right'
                    });
                });
            });
        });
    });

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
