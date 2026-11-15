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

<script src="{{ asset('assets/js/gr-toast.js') }}?v={{ filemtime(public_path('assets/js/gr-toast.js')) }}"></script>
<script>
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2()
    });

    // ========== Messages: same toasts as the storefront (gr-toast.js) ==========
    // toastr was called in a few tables but never loaded; route it to the toast too.
    window.toastr = {
        success: (m) => grToast('success', m),
        error: (m) => grToast('error', m),
        info: (m) => grToast('info', m),
        warning: (m) => grToast('warning', m),
    };

    document.addEventListener('DOMContentLoaded', () => {
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
    });
</script>
@vite('resources/js/app.js')
