<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>
    WallAve Dashboard
</title>

<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="{{ asset('dashboard/plugins/fontawesome-free/css/all.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('dashboard/dist/css/adminlte.min.css') }}">
{{-- toastr css --}}
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}">
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
<link rel="icon" href="{{ asset('assets/img/favicon.png') }}" type="image/gif" sizes="20x20">

@vite(['resources/js/app.js', 'resources/css/app.css'])

<style>
    .ck-editor__editable[role="textbox"] {
        /* Editing area */
        min-height: 150px;
    }
</style>
