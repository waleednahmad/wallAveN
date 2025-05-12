<!-- jQuery -->
<script src="{{ asset('dashboard/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dashboard/js/bootstrap.min.js') }}"></script>


{{-- <script src="{{ asset('dashboard/plugins/popper/umd/popper.min.js') }}"></script> --}}
<!-- AdminLTE App -->
<script src="{{ asset('dashboard/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('assets/js/popper.min.js') }}"></script>

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

    document.addEventListener('livewire:init', () => {
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
</script>
@vite('resources/js/app.js')
