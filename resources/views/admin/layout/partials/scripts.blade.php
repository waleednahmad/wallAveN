<!-- jQuery -->
<script src="{{ asset('dashboard/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dashboard/js/bootstrap.min.js') }}"></script>


{{-- <script src="{{ asset('dashboard/plugins/popper/umd/popper.min.js') }}"></script> --}}
<!-- AdminLTE App -->
<script src="{{ asset('dashboard/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('assets/js/popper.min.js') }}"></script>


{{-- toastr --}}
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>

{{-- Select2 --}}
<script src="{{ asset('dashboard/plugins/select2/js/select2.full.min.js') }}"></script>

{{-- ChartJs --}}
<script src="{{ asset('dashboard/plugins/chart.js/Chart.js') }}"></script>
<script src="{{ asset('dashboard/plugins/chart.js/Chart.bundle.js') }}"></script>


{{-- Texe Editor --}}
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css"
    integrity="sha512-MQXduO8IQnJVq1qmySpN87QQkiR1bZHtorbJBD0tzy7/0U9+YIC93QWHeGTEoojMVHWWNkoCp8V6OzVSYrX0oQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

<script>
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2()
    });
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "6000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

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

        // Error Validation Message
        Livewire.on('validationFailed', (event) => {
            event[0].forEach((error) => {
                toastr.error(error);
            });
        });
    });
</script>
@vite('resources/js/app.js')
