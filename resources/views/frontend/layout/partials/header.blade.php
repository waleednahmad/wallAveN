<div class="topbar">
    <div class="container">
        <div class="topbar-wrap">
            <div class="header-logo">
                <a href="{{ route('frontend.home') }}">
                    <img alt="image" class="img-fluid light" src="{{ getMainImage() }}" style="width: 150px;">
                    <img alt="image" class="img-fluid dark" src="{{ getMainImage() }}" style="width: 150px;">
                    {{-- <img alt="image" class="img-fluid light" src="{{ asset('assets/img/header-logo.svg') }}"> --}}
                    {{-- <img alt="image" class="img-fluid dark" src="{{ asset('assets/img/header-logo-white.svg') }}"> --}}
                </a>
            </div>
            <div class="search-area">
                <form action="{{ route('frontend.shop') }}">
                    <div class="form-inner">
                        <input type="text" name="search" placeholder="Search your product here">
                        <button type="submit">
                            <svg width="17" height="17" viewBox="0 0 17 17" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M7.20349 0.448242C5.41514 0.45124 3.70089 1.16299 2.43633 2.42755C1.17178 3.6921 0.460029 5.40635 0.457031 7.1947C0.458526 8.98456 1.16943 10.7008 2.43399 11.9675C3.69855 13.2342 5.41364 13.948 7.20349 13.9525C8.79089 13.9525 10.2536 13.3941 11.4101 12.47L15.0578 16.1179C15.2002 16.2503 15.3882 16.3223 15.5825 16.3189C15.7768 16.3155 15.9622 16.2369 16.0998 16.0997C16.2374 15.9625 16.3165 15.7773 16.3204 15.583C16.3243 15.3887 16.2528 15.2005 16.1208 15.0578L12.4731 11.407C13.4325 10.2138 13.9556 8.72863 13.9556 7.19753C13.9556 3.47848 10.9225 0.448242 7.20349 0.448242ZM7.20349 1.9506C10.1118 1.9506 12.4533 4.28919 12.4533 7.1947C12.4533 10.1002 10.1118 12.453 7.20349 12.453C4.29514 12.453 1.95656 10.1087 1.95656 7.20037C1.95656 4.29202 4.29514 1.9506 7.20349 1.9506Z" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
            <div class="topbar-right">
                {{-- Cart Offcanva Button --}}

                <a href="#" class="wishlist-btn" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanva"
                    aria-controls="cartOffcanva">
                    <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm0 2c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm10-2c-1.1 0-1.99.9-1.99 2S15.9 22 17 22s2-.9 2-2-.9-2-2-2zm0 2c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zM7.82 14.18l.03-.12L9.1 10H19c.55 0 1-.45 1-1s-.45-1-1-1H9.42l-.94-4.34C8.39 3.26 7.74 3 7.05 3H4c-.55 0-1 .45-1 1s.45 1 1 1h2.05l1.1 5.06L5.6 14.59c-.09.26-.14.54-.14.83 0 1.1.9 2 2 2h12c.55 0 1-.45 1-1s-.45-1-1-1H7.82z" />
                    </svg>
                </a>

                <a @auth('dealer')
                        href="{{ route('dealer.dashboard') }}"
                    @endauth
                    @auth('representative')
                        href="{{ route('representative.dashboard') }}"
                    @endauth
                    @auth('web')
                        href="{{ route('dashboard') }}"
                    @endauth
                    @guest('dealer')
                        href="{{ route('login') }}"
                    @endguest
                    @guest('representative')
                        href="{{ route('login') }}"
                    @endguest
                    @guest('web')
                        href="{{ route('login') }}"
                    @endguest
                    class="header-btn btn-hover">
                    <svg width="14" height="14" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg">
                        <g>
                            <path
                                d="M7.7479 6.74386C8.67435 6.74386 9.47651 6.41155 10.1321 5.75593C10.7875 5.10045 11.1199 4.2984 11.1199 3.37183C11.1199 2.44554 10.7876 1.64341 10.132 0.987682C9.4764 0.332281 8.67425 0 7.7479 0C6.8213 0 6.01923 0.332281 5.36374 0.987791C4.70826 1.6433 4.37584 2.44546 4.37584 3.37183C4.37584 4.2984 4.70823 5.10056 5.36377 5.75607C6.01947 6.41147 6.82163 6.74386 7.74787 6.74386H7.7479ZM5.94395 1.56789C6.44691 1.0649 7.03696 0.82042 7.7479 0.82042C8.45872 0.82042 9.04885 1.0649 9.55192 1.56789C10.0549 2.07096 10.2995 2.66109 10.2995 3.37181C10.2995 4.08274 10.0549 4.67279 9.55192 5.17586C9.04885 5.67896 8.45872 5.92344 7.7479 5.92344C7.03715 5.92344 6.44716 5.67885 5.94395 5.17586C5.44085 4.6729 5.19626 4.08277 5.19626 3.37183C5.19626 2.66109 5.44085 2.07096 5.94395 1.56789ZM13.648 10.7653C13.6291 10.4925 13.5909 10.1949 13.5346 9.8807C13.4778 9.56409 13.4046 9.26481 13.317 8.99126C13.2265 8.70853 13.1035 8.42935 12.9513 8.16177C12.7935 7.88406 12.6081 7.64223 12.4 7.44325C12.1825 7.23509 11.9161 7.06772 11.608 6.9456C11.3011 6.82419 10.9609 6.76267 10.597 6.76267C10.454 6.76267 10.3159 6.8213 10.0489 6.99509C9.85918 7.11864 9.66888 7.24133 9.47801 7.36314C9.29462 7.48 9.04617 7.58949 8.7393 7.68861C8.43991 7.78549 8.13593 7.83462 7.83578 7.83462C7.53587 7.83462 7.23186 7.78549 6.93226 7.68861C6.62574 7.58957 6.37718 7.48011 6.19409 7.36325C5.98163 7.22749 5.7894 7.10359 5.62266 6.99495C5.35595 6.82119 5.21773 6.76253 5.07483 6.76253C4.7108 6.76253 4.37073 6.82417 4.06385 6.94574C3.75602 7.06761 3.48952 7.23498 3.27173 7.44336C3.06367 7.64245 2.87826 7.88414 2.72059 8.16177C2.56862 8.42932 2.44557 8.70842 2.35498 8.99137C2.26748 9.26492 2.19434 9.56409 2.13752 9.8807C2.08111 10.1945 2.04299 10.4922 2.02407 10.7656C2.00547 11.033 1.99609 11.3112 1.99609 11.5923C1.99609 12.3231 2.22841 12.9148 2.68652 13.3511C3.13898 13.7817 3.73756 14 4.46567 14H11.2068C11.9347 14 12.5332 13.7817 12.9858 13.3511C13.444 12.9151 13.6763 12.3233 13.6763 11.5922C13.6762 11.3101 13.6667 11.0319 13.648 10.7653ZM12.4201 12.7567C12.1212 13.0412 11.7242 13.1796 11.2066 13.1796H4.46569C3.948 13.1796 3.55108 13.0412 3.25221 12.7568C2.95903 12.4777 2.81654 12.0967 2.81654 11.5923C2.81654 11.33 2.82518 11.071 2.84252 10.8223C2.85936 10.5784 2.89387 10.3104 2.94506 10.0256C2.99556 9.74439 3.05985 9.48047 3.13633 9.24154C3.20972 9.0124 3.30979 8.78556 3.43391 8.56703C3.55236 8.35872 3.68864 8.18003 3.83903 8.03607C3.97971 7.90137 4.15704 7.79115 4.36594 7.70849C4.55916 7.63198 4.77632 7.59012 5.01205 7.5838C5.04076 7.59911 5.09195 7.62826 5.17483 7.68229C5.34348 7.79222 5.53787 7.91761 5.75279 8.05485C5.99505 8.20932 6.30713 8.3488 6.68001 8.46916C7.06121 8.59243 7.45001 8.65502 7.83591 8.65502C8.22184 8.65502 8.61073 8.59243 8.99173 8.46927C9.36492 8.34869 9.67691 8.20932 9.9195 8.05463C10.1394 7.91409 10.3284 7.79232 10.497 7.68229C10.5799 7.62834 10.6311 7.59909 10.6598 7.58383C10.8956 7.59012 11.1128 7.63198 11.3061 7.70846C11.5149 7.79115 11.6923 7.90148 11.8329 8.03604C11.9833 8.17992 12.1196 8.35864 12.238 8.56713C12.3623 8.78556 12.4625 9.01254 12.5357 9.24143C12.6123 9.48069 12.6767 9.7445 12.7271 10.0255C12.7782 10.3108 12.8128 10.5789 12.8297 10.8224V10.8227C12.8471 11.0703 12.8558 11.3293 12.8559 11.5923C12.8558 12.0968 12.7133 12.4777 12.4202 12.7567H12.4201Z" />
                        </g>
                    </svg>
                    <span>
                        @if (auth('dealer')->check() || auth('representative')->check() || auth('web')->check())
                            @if (auth('web')->check())
                                Dashboard
                            @else
                                My Account
                            @endif
                        @else
                            Login
                        @endif
                    </span>
                    <strong></strong>
                </a>
            </div>
        </div>
    </div>
</div>
<header class="header-area style-1">
    <div class="container d-flex flex-nowrap align-items-center justify-content-between">
        <div class="header-logo d-lg-none d-block">
            <a href="{{ route('frontend.home') }}">
                <img alt="image" class="img-fluid light" src="{{ asset('assets/img/header-logo.svg') }}">
                <img alt="image" class="img-fluid dark" src="{{ asset('assets/img/header-logo-white.svg') }}">
            </a>
        </div>
        <div class="main-menu">
            <div class="mobile-logo-area d-lg-none d-flex justify-content-center">
                <a href="{{ route('frontend.home') }}" class="mobile-logo-wrap">
                    <img alt="image" class="img-fluid light" src="{{ asset('assets/img/header-logo.svg') }}">
                    <img alt="image" class="img-fluid dark" src="{{ asset('assets/img/header-logo-white.svg') }}">
                </a>
            </div>
   
            <ul class="menu-list">
                <li><a href="{{ route('frontend.home') }}">Home</a></li>
                <li><a href="{{ route('frontend.aboutUs') }}">About us</a></li>
                <li><a href="{{ route('frontend.shop') }}">Shop</a></li>
                @if (isset($publicActiveCategories) && $publicActiveCategories->count() > 0)
                    <li class="menu-item-has-children">
                        <a href="#" class="drop-down">Categories</a>
                        <i class="bi bi-plus dropdown-icon"></i>
                        <ul class="sub-menu two" style="z-index: 9999;">
                            @foreach ($publicActiveCategories as $category)
                                <li>
                                    <a href="{{ route('frontend.shop', ['categories' => [$category->id]]) }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
                <li><a href="{{ route('frontend.contactUs') }}">Contact us</a></li>
                @if (auth('dealer')->check() || auth('representative')->check() || auth('web')->check())
                    {{-- Dashboard --}}
                    <li>
                        @php
                            $path = null;
                            $logoutPath = null;
                            if (auth('dealer')->check()) {
                                $path = 'dealer.dashboard';
                                $logoutPath = 'frontend.logout';
                            } elseif (auth('representative')->check()) {
                                $path = 'representative.dashboard';
                                $logoutPath = 'representative.logout';
                            } else {
                                $path = 'dashboard';
                                $logoutPath = 'logout';
                            }
                        @endphp
                        <a href="{{ route($path) }}">
                            Dashboard
                        </a>
                    </li>

                    {{-- Logout --}}
                    <li>
                        <a href="{{ route($logoutPath) }}">
                            Logout
                        </a>
                    </li>
                @else
                    <li><a href="{{ route('frontend.register') }}">Become A Dealer</a></li>
                    <li><a href="{{ route('login') }}">Login</a></li>
                @endif


            </ul>
            <div class="search-area d-lg-none d-block">
                <form action="{{ route('frontend.shop') }}">
                    <div class="form-inner">
                        <input type="text" name="search" placeholder="Search your product">
                        <button type="submit">
                            <svg width="17" height="17" viewBox="0 0 17 17" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M7.20349 0.448242C5.41514 0.45124 3.70089 1.16299 2.43633 2.42755C1.17178 3.6921 0.460029 5.40635 0.457031 7.1947C0.458526 8.98456 1.16943 10.7008 2.43399 11.9675C3.69855 13.2342 5.41364 13.948 7.20349 13.9525C8.79089 13.9525 10.2536 13.3941 11.4101 12.47L15.0578 16.1179C15.2002 16.2503 15.3882 16.3223 15.5825 16.3189C15.7768 16.3155 15.9622 16.2369 16.0998 16.0997C16.2374 15.9625 16.3165 15.7773 16.3204 15.583C16.3243 15.3887 16.2528 15.2005 16.1208 15.0578L12.4731 11.407C13.4325 10.2138 13.9556 8.72863 13.9556 7.19753C13.9556 3.47848 10.9225 0.448242 7.20349 0.448242ZM7.20349 1.9506C10.1118 1.9506 12.4533 4.28919 12.4533 7.1947C12.4533 10.1002 10.1118 12.453 7.20349 12.453C4.29514 12.453 1.95656 10.1087 1.95656 7.20037C1.95656 4.29202 4.29514 1.9506 7.20349 1.9506Z" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
            <div class="btn-area d-lg-none d-flex justify-content-center">
                <a href="dashboard.html" class="header-btn btn-hover">
                    <svg width="14" height="14" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg">
                        <g>
                            <path
                                d="M7.7479 6.74386C8.67435 6.74386 9.47651 6.41155 10.1321 5.75593C10.7875 5.10045 11.1199 4.2984 11.1199 3.37183C11.1199 2.44554 10.7876 1.64341 10.132 0.987682C9.4764 0.332281 8.67425 0 7.7479 0C6.8213 0 6.01923 0.332281 5.36374 0.987791C4.70826 1.6433 4.37584 2.44546 4.37584 3.37183C4.37584 4.2984 4.70823 5.10056 5.36377 5.75607C6.01947 6.41147 6.82163 6.74386 7.74787 6.74386H7.7479ZM5.94395 1.56789C6.44691 1.0649 7.03696 0.82042 7.7479 0.82042C8.45872 0.82042 9.04885 1.0649 9.55192 1.56789C10.0549 2.07096 10.2995 2.66109 10.2995 3.37181C10.2995 4.08274 10.0549 4.67279 9.55192 5.17586C9.04885 5.67896 8.45872 5.92344 7.7479 5.92344C7.03715 5.92344 6.44716 5.67885 5.94395 5.17586C5.44085 4.6729 5.19626 4.08277 5.19626 3.37183C5.19626 2.66109 5.44085 2.07096 5.94395 1.56789ZM13.648 10.7653C13.6291 10.4925 13.5909 10.1949 13.5346 9.8807C13.4778 9.56409 13.4046 9.26481 13.317 8.99126C13.2265 8.70853 13.1035 8.42935 12.9513 8.16177C12.7935 7.88406 12.6081 7.64223 12.4 7.44325C12.1825 7.23509 11.9161 7.06772 11.608 6.9456C11.3011 6.82419 10.9609 6.76267 10.597 6.76267C10.454 6.76267 10.3159 6.8213 10.0489 6.99509C9.85918 7.11864 9.66888 7.24133 9.47801 7.36314C9.29462 7.48 9.04617 7.58949 8.7393 7.68861C8.43991 7.78549 8.13593 7.83462 7.83578 7.83462C7.53587 7.83462 7.23186 7.78549 6.93226 7.68861C6.62574 7.58957 6.37718 7.48011 6.19409 7.36325C5.98163 7.22749 5.7894 7.10359 5.62266 6.99495C5.35595 6.82119 5.21773 6.76253 5.07483 6.76253C4.7108 6.76253 4.37073 6.82417 4.06385 6.94574C3.75602 7.06761 3.48952 7.23498 3.27173 7.44336C3.06367 7.64245 2.87826 7.88414 2.72059 8.16177C2.56862 8.42932 2.44557 8.70842 2.35498 8.99137C2.26748 9.26492 2.19434 9.56409 2.13752 9.8807C2.08111 10.1945 2.04299 10.4922 2.02407 10.7656C2.00547 11.033 1.99609 11.3112 1.99609 11.5923C1.99609 12.3231 2.22841 12.9148 2.68652 13.3511C3.13898 13.7817 3.73756 14 4.46567 14H11.2068C11.9347 14 12.5332 13.7817 12.9858 13.3511C13.444 12.9151 13.6763 12.3233 13.6763 11.5922C13.6762 11.3101 13.6667 11.0319 13.648 10.7653ZM12.4201 12.7567C12.1212 13.0412 11.7242 13.1796 11.2066 13.1796H4.46569C3.948 13.1796 3.55108 13.0412 3.25221 12.7568C2.95903 12.4777 2.81654 12.0967 2.81654 11.5923C2.81654 11.33 2.82518 11.071 2.84252 10.8223C2.85936 10.5784 2.89387 10.3104 2.94506 10.0256C2.99556 9.74439 3.05985 9.48047 3.13633 9.24154C3.20972 9.0124 3.30979 8.78556 3.43391 8.56703C3.55236 8.35872 3.68864 8.18003 3.83903 8.03607C3.97971 7.90137 4.15704 7.79115 4.36594 7.70849C4.55916 7.63198 4.77632 7.59012 5.01205 7.5838C5.04076 7.59911 5.09195 7.62826 5.17483 7.68229C5.34348 7.79222 5.53787 7.91761 5.75279 8.05485C5.99505 8.20932 6.30713 8.3488 6.68001 8.46916C7.06121 8.59243 7.45001 8.65502 7.83591 8.65502C8.22184 8.65502 8.61073 8.59243 8.99173 8.46927C9.36492 8.34869 9.67691 8.20932 9.9195 8.05463C10.1394 7.91409 10.3284 7.79232 10.497 7.68229C10.5799 7.62834 10.6311 7.59909 10.6598 7.58383C10.8956 7.59012 11.1128 7.63198 11.3061 7.70846C11.5149 7.79115 11.6923 7.90148 11.8329 8.03604C11.9833 8.17992 12.1196 8.35864 12.238 8.56713C12.3623 8.78556 12.4625 9.01254 12.5357 9.24143C12.6123 9.48069 12.6767 9.7445 12.7271 10.0255C12.7782 10.3108 12.8128 10.5789 12.8297 10.8224V10.8227C12.8471 11.0703 12.8558 11.3293 12.8559 11.5923C12.8558 12.0968 12.7133 12.4777 12.4202 12.7567H12.4201Z" />
                        </g>
                    </svg>
                    <span>My Account</span>
                    <strong></strong>
                </a>
            </div>
        </div>

        <div class="nav-right">
            <div class="dark-light-switch d-lg-none d-block">
                <i class="bi bi-brightness-low-fill"></i>
            </div>
            <div class="sidebar-button mobile-menu-btn">
                <span></span>
            </div>
        </div>
    </div>
    @if (auth('representative')->check() || auth('web')->check())
        <div class="container alert alert-info alert-dismissible show d-block" role="alert">
            <div class="alert-body">
                @php
                    $dealer = auth('representative')->user()?->buyingFor ?? auth('web')->user()?->buyingFor;
                    $dealerName = $dealer ? $dealer->name : 'No dealer selected';
                @endphp
                @if ($dealer)
                    <div class="d-flex justify-content-between align-items-center">
                        <p>
                            You are shopping as
                            <b>
                                {{ $dealerName }}
                            </b>
                        </p>

                        {{-- Open Change dealer Modal --}}
                        <button type="button" class="btn btn-hover" data-bs-toggle="modal"
                            style="background-color: black; color: white;" data-bs-target="#DealerSelection">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    </div>
                @else
                    <div class="d-flex justify-content-between align-items-center">
                        <p>
                            You must select a dealer to continue shopping
                        </p>

                        {{-- Open Change dealer Modal --}}
                        <button type="button" class="btn btn-hover" data-bs-toggle="modal"
                            style="background-color: black; color: white;" data-bs-target="#DealerSelection">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</header>
