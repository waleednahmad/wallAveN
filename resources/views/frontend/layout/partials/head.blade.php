<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap CSS -->
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<!-- Bootstrap Icon CSS -->
<link href="{{ asset('assets/css/bootstrap-icons.css') }}" rel="stylesheet">
<!-- Swiper slider CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}">
<!--Nice Select CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/nice-select.css') }}">

<!-- Animate CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/jquery.fancybox.min.css') }}">

{{-- toastr css --}}
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}">

<!-- BoxIcon  CSS -->
<link href="{{ asset('assets/css/boxicons.min.css') }}" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

<link rel="stylesheet" href="{{ asset('dashboard/plugins/fontawesome-free/css/all.min.css') }}">
<!-- Title -->
<title>
    {{ getWebsiteTitle() }} | @yield('title', 'Home')
</title>
<link rel="icon" href="{{ getFavicon() }}" type="image/gif" sizes="20x20">

<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">


<!-- Google tag (gtag.js) -->
{!! getGoogleAnalytics() !!}


<style>
    .text-container {
        display: -webkit-box !important;
        -webkit-line-clamp: 2 !important;
        /* Number of lines to display */
        -webkit-box-orient: vertical !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
</style>
