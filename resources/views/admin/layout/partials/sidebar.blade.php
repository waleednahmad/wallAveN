<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link d-flex flex-column justify-items-center align-items-center">
        <img src="{{ asset('assets/img/header-logo-white.svg') }}" alt="">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="pb-3 mt-3 mb-3 user-panel d-flex">
            <div class="info d-flex align-items-center">
                <a href="#" class="d-block">
                    {{ auth()->user()->name }}
                </a>
                <a class="py-1 ml-3 btn btn-sm btn-danger" href="{{ route('logout') }}">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                {{-- Home link --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" @class(['nav-link', 'active' => request()->routeIs('dashboard')])>
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>

                {{-- -------------------- ADMIN -------------------- --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard.admins.index') }}" @class([
                        'nav-link',
                        'active' => request()->routeIs('dashboard.admins.*'),
                    ])>
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Admins
                        </p>
                    </a>
                </li>

                {{-- -------------------- Products -------------------- --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard.products.index') }}" @class([
                        'nav-link',
                        'active' => request()->routeIs('dashboard.products.*'),
                    ])>
                        <i class="nav-icon fas fa-box"></i>
                        <p>
                            Products
                        </p>
                    </a>
                </li>

                {{-- -------------------- Dealers -------------------- --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard.dealers.index') }}" @class([
                        'nav-link',
                        'active' => request()->routeIs('dashboard.dealers.*'),
                    ])>
                        <i class="nav-icon fas fa-store"></i>
                        <p>
                            Dealers
                        </p>
                    </a>
                </li>

                {{-- -------------------- Orders -------------------- --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard.orders.index') }}" @class([
                        'nav-link',
                        'active' => request()->routeIs('dashboard.orders.*'),
                    ])>
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>
                            Orders
                        </p>
                    </a>
                </li>

                {{-- -------------------- Representatives -------------------- --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard.representatives.index') }}" @class([
                        'nav-link',
                        'active' => request()->routeIs('dashboard.representatives.*'),
                    ])>
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Representatives
                        </p>
                    </a>
                </li>

                {{-- -------------------- Public Settings -------------------- --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard.public-settings.index') }}" @class([
                        'nav-link',
                        'active' => request()->routeIs('dashboard.public-settings.*'),
                    ])>
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Public Settings
                        </p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
