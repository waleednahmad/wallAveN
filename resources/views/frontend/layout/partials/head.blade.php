<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

{{-- SEO and sharing. Pages set title / meta_desc / keywords; optional og_image, og_type, robots, canonical. --}}
@php
    $grSite = getWebsiteTitle() ?: config('app.name');
    $grDesc = trim($__env->yieldContent('meta_desc'));
    $grShareImage = trim($__env->yieldContent('og_image'))
        ?: (file_exists(public_path('assets/img/home1/about-img1.webp')) ? asset('assets/img/home1/about-img1.webp') : getMainImage());
    $grCanonical = trim($__env->yieldContent('canonical')) ?: url()->current();
@endphp
<meta name="description" content="{!! $grDesc !!}">
<meta name="keywords" content="@yield('keywords')">
<meta name="robots" content="@yield('robots', 'index, follow')">
<link rel="canonical" href="{{ $grCanonical }}">
<meta name="author" content="{{ $grSite }}">
<meta property="og:site_name" content="{{ $grSite }}">
<meta property="og:type" content="@yield('og_type', 'website')">
<meta property="og:url" content="{{ $grCanonical }}">
<meta property="og:title" content="@yield('title')">
<meta property="og:description" content="{!! $grDesc !!}">
<meta property="og:image" content="{{ $grShareImage }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('title')">
<meta name="twitter:description" content="{!! $grDesc !!}">
<meta name="twitter:image" content="{{ $grShareImage }}">
@if (request()->routeIs('frontend.home'))
    {{-- Who the business is, for search engines --}}
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $grSite,
        'url' => url('/'),
        'logo' => getMainImage(),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endif

<!-- Bootstrap CSS -->
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<!-- Bootstrap Icon CSS -->
<link href="{{ asset('assets/css/bootstrap-icons.css') }}" rel="stylesheet">


<meta name="csrf-token" content="{{ csrf_token() }}">


<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ filemtime(public_path('assets/css/style.css')) }}">

<!-- 2026 redesign: brand fonts + theme (loaded after the template stylesheet so it wins) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;1,9..144,400&family=Inter:wght@400;500;600&display=swap">
<link rel="stylesheet" href="{{ asset('assets/css/brand.css') }}?v={{ filemtime(public_path('assets/css/brand.css')) }}">
<link rel="stylesheet" href="{{ asset('assets/css/gr-theme.css') }}?v={{ filemtime(public_path('assets/css/gr-theme.css')) }}">
<link rel="stylesheet" href="{{ asset('assets/css/gr-toast.css') }}?v={{ filemtime(public_path('assets/css/gr-toast.css')) }}">

<link rel="stylesheet" href="{{ asset('dashboard/plugins/fontawesome-free/css/all.min.css') }}">
<!-- Title -->
{{-- Home: the SEO title as written (it should include the company name); other pages: "Page title | Company".
     The title section is already escaped by @section('title', ...), so it is printed as-is, like @yield. --}}
@php $grPageTitle = trim($__env->yieldContent('title')); @endphp
<title>
    @if (request()->routeIs('frontend.home'))
        {!! $grPageTitle !== '' && $grPageTitle !== 'Home' ? $grPageTitle : e(getWebsiteTitle()) !!}
    @else
        {!! $grPageTitle !== '' ? $grPageTitle : 'Home' !!} | {{ getWebsiteTitle() }}
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
