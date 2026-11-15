<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>
    {{ getWebsiteTitle() }} Dashboard
</title>

<!-- Brand fonts (same as the storefront) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;1,9..144,400&family=Inter:wght@400;500;600&display=swap">
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="{{ asset('dashboard/plugins/fontawesome-free/css/all.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('dashboard/dist/css/adminlte.min.css') }}">

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('dashboard/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('dashboard/css/bootstrap.min.css') }}">

{{-- =================================== --}}
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

{{-- ChartJs --}}
<link rel="stylesheet" href="{{ asset('dashboard/plugins/chart.js/Chart.css') }}">

{{-- =================================== --}}
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<link rel="icon" href="{{ getFavicon() }}" type="image/gif" sizes="20x20">

@vite(['resources/js/app.js', 'resources/css/app.css'])

{{-- Brand tokens + admin theme: loaded last so they win over AdminLTE and Bootstrap --}}
<link rel="stylesheet" href="{{ asset('assets/css/brand.css') }}?v={{ filemtime(public_path('assets/css/brand.css')) }}">
<link rel="stylesheet" href="{{ asset('assets/css/gr-admin.css') }}?v={{ filemtime(public_path('assets/css/gr-admin.css')) }}">
<link rel="stylesheet" href="{{ asset('assets/css/gr-toast.css') }}?v={{ filemtime(public_path('assets/css/gr-toast.css')) }}">

<style>
    .ck-editor__editable[role="textbox"] {
        /* Editing area */
        min-height: 150px;
    }
</style>
