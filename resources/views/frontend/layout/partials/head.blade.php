<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="title" content="@yield('title')">
<meta name="description" content="@yield('meta_desc')">
<meta name="keywords" content="@yield('keywords')">
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:image:width" content="200" />
<meta property="og:image:height" content="200" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@goldenRugs" />
<meta name="twitter:creator" content="@goldenRugs" />
<meta name="twitter:title" content="@yield('title')">
<meta name="twitter:description" content="@yield('meta_desc')" />
<meta name="author" content="golden Rugs">
<meta property="og:title" content="@yield('title')">
<meta property="og:description" content="@yield('meta_desc')">
<meta propert="og:keywords" content="@yield('keywords')">

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
    @if (request()->routeIs('frontend.home'))
        {{ getWebsiteTitle() }}
    @else
        @yield('title', 'Home') | {{ getWebsiteTitle() }}
    @endif
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

    /* When ul.sub-menu is empty */
    header.style-1 .main-menu ul>li.menu-item-has-children.sub-menu-empty::after {
        content: '' !important;
    }
</style>
