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

{{-- toastr --}}
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>


<script src="{{ asset('assets/js/main.js') }}"></script>


<script type="speculationrules">
    {
        "prerender" : [
            {
                "urls" : [
                    "/" ,"/shop" ,"/login" ,"/register", "representative/register"
                ],
                "eagrness" : "moderate"
            }
        ]
    }
  </script>

<script>
    // ========== Toaster Mssages ==========
    // success messages
    @if (session()->has('success'))
        toastr.success('{{ session()->get('success') }}')
    @endif
    // error messages
    @if (session()->has('error'))
        toastr.error('{{ session()->get('error') }}')
    @endif

    //  error validation messages
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            toastr.error('{{ $error }}')
        @endforeach
    @endif
    // ========== End Toaster Mssages ==========

    document.addEventListener('livewire:init', () => {
        // Success Message
        Livewire.on('success', (event) => {
            toastr.success(event[0]);
        });
        // Error Message
        Livewire.on('error', (event) => {
            toastr.error(event[0]);
        });
        // Info Message
        Livewire.on('info', (event) => {
            toastr.info(event[0]);
        });
        // Warning Message
        Livewire.on('warning', (event) => {
            toastr.warning(event[0]);
        });
    });
</script>
