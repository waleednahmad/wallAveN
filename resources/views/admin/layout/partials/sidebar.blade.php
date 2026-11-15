<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    {{-- Workspace block: the company's logo tile + name (SaaS: one per company) --}}
    <a href="{{ route('dashboard') }}" class="brand-link adm-ws">
        <span class="adm-ws-logo"><img src="{{ getMainImage() }}" alt=""></span>
        <span class="adm-ws-text">
            <span class="adm-ws-name">{{ getWebsiteTitle() ?: config('app.name') }}</span>
            <span class="adm-ws-sub">Wholesale workspace</span>
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
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

                <li @class([
                    'nav-item',
                    'menu-open' =>
                        request()->routeIs('dashboard.admins.*') ||
                        request()->routeIs('dashboard.vendors.*') ||
                        request()->routeIs('dashboard.dealers.*') ||
                        request()->routeIs('dashboard.representatives.*'),
                ])>
                    <a href="#" @class([
                        'nav-link',
                        'active' =>
                            request()->routeIs('dashboard.admins.*') ||
                            request()->routeIs('dashboard.vendors.*') ||
                            request()->routeIs('dashboard.dealers.*') ||
                            request()->routeIs('dashboard.representatives.*'),
                    ])>
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Users
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        {{-- Admins --}}
                        <li class="nav-item">
                            <a href="{{ route('dashboard.admins.index') }}" @class([
                                'nav-link',
                                'active' => request()->routeIs('dashboard.admins.*'),
                            ])>
                                <i class="far fa-circle nav-icon"></i>

                                <p>
                                    Admins
                                </p>
                            </a>
                        </li>
                        {{-- Dealers --}}
                        <li class="nav-item">
                            <a href="{{ route('dashboard.dealers.index') }}" @class([
                                'nav-link',
                                'active' => request()->routeIs('dashboard.dealers.*'),
                            ])>
                                <i class="far fa-circle nav-icon"></i>

                                <p>
                                    Dealers
                                </p>
                            </a>
                        </li>
                        {{-- Representatives --}}
                        <li class="nav-item">
                            <a href="{{ route('dashboard.representatives.index') }}" @class([
                                'nav-link',
                                'active' => request()->routeIs('dashboard.representatives.*'),
                            ])>
                                <i class="far fa-circle nav-icon"></i>

                                <p>
                                    Representatives
                                </p>
                            </a>
                        </li>
                        {{-- Vendors --}}
                        <li class="nav-item">
                            <a href="{{ route('dashboard.vendors.index') }}" @class([
                                'nav-link',
                                'active' => request()->routeIs('dashboard.vendors.*'),
                            ])>
                                <i class="far fa-circle nav-icon"></i>

                                <p>
                                    Vendors
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>


                {{-- -------------------- Categories -------------------- --}}
                <li @class([
                    'nav-item',
                    'menu-open' =>
                        request()->routeIs('dashboard.categories.*') ||
                        request()->routeIs('dashboard.sub-categories.*') ||
                        request()->routeIs('dashboard.product-types.*'),
                ])>
                    <a href="#" @class([
                        'nav-link',
                        'active' =>
                            request()->routeIs('dashboard.categories.*') ||
                            request()->routeIs('dashboard.sub-categories.*') ||
                            request()->routeIs('dashboard.product-types.*'),
                    ])>
                        <i class="nav-icon fas fa-th-list"></i>
                        <p>
                            Categories
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        {{-- Categories --}}
                        <li class="nav-item">
                            <a href="{{ route('dashboard.categories.index') }}" @class([
                                'nav-link',
                                'active' => request()->routeIs('dashboard.categories.*'),
                            ])>
                                <i class="far fa-circle nav-icon"></i>

                                <p>
                                    Categories
                                </p>
                            </a>
                        </li>
                        {{-- Sub Categories --}}
                        <li class="nav-item">
                            <a href="{{ route('dashboard.sub-categories.index') }}" @class([
                                'nav-link',
                                'active' => request()->routeIs('dashboard.sub-categories.*'),
                            ])>
                                <i class="far fa-circle nav-icon"></i>

                                <p>
                                    Sub Categories
                                </p>
                            </a>
                        </li>
                        {{-- Product Type --}}
                        <li class="nav-item">
                            <a href="{{ route('dashboard.product-types.index') }}" @class([
                                'nav-link',
                                'active' => request()->routeIs('dashboard.product-types.*'),
                            ])>
                                <i class="far fa-circle nav-icon"></i>

                                <p>
                                    Product Type
                                </p>
                            </a>
                        </li>

                    </ul>
                </li>

                {{-- -------------------- Attributes -------------------- --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard.attributes.index') }}" @class([
                        'nav-link',
                        'active' => request()->routeIs('dashboard.attributes.*'),
                    ])>
                        <i class="nav-icon fas fa-tags"></i>
                        <p>
                            Attributes
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


                {{-- -------------------- Orders -------------------- --}}
                <li @class([
                    'nav-item',
                    'menu-open' => request()->routeIs('dashboard.orders.*'),
                ])>
                    <a href="#" @class([
                        'nav-link',
                        'active' => request()->routeIs('dashboard.orders.*'),
                    ])>
                        <i class="nav-icon fas fa-shopping-cart"></i>

                        <p>
                            Orders
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        {{-- orders --}}
                        <li class="nav-item">
                            <a href="{{ route('dashboard.orders.index') }}" @class([
                                'nav-link',
                                'active' => request()->routeIs('dashboard.orders.*'),
                            ])>
                                <i class="far fa-circle nav-icon"></i>

                                <p>
                                    Orders
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- -------------------- Abonend Checkouts -------------------- --}}
                <li @class([
                    'nav-item',
                    'menu-open' =>
                        request()->routeIs('dashboard.abandoned-orders.*') ||
                        request()->routeIs('dashboard.all-admins-dealers-abandoned-carts') ||
                        request()->routeIs(
                            'dashboard.all-representatives-dealers-abandoned-carts') ||
                        request()->routeIs('dashboard.admin-dealers-abandoned-carts') ||
                        request()->routeIs('dashboard.representative-dealers-abandoned-carts'),
                ])>
                    <a href="#" @class([
                        'nav-link',
                        'active' =>
                            request()->routeIs('dashboard.abandoned-orders.*') ||
                            request()->routeIs('dashboard.all-admins-dealers-abandoned-carts') ||
                            request()->routeIs(
                                'dashboard.all-representatives-dealers-abandoned-carts') ||
                            request()->routeIs('dashboard.admin-dealers-abandoned-carts') ||
                            request()->routeIs('dashboard.representative-dealers-abandoned-carts'),
                    ])>
                        <i class="nav-icon fas fa-shopping-cart"></i>

                        <p>
                            Abandoned Checkouts
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        {{-- Abounde Checkout --}}
                        <li class="nav-item">
                            <a href="{{ route('dashboard.abandoned-orders.index') }}" @class([
                                'nav-link',
                                'active' => request()->routeIs('dashboard.abandoned-orders.*'),
                            ])>
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Dealers Carts
                                </p>
                            </a>
                        </li>
                        {{-- Admin Dealers Abandoned Carts --}}
                        {{-- <li class="nav-item">
                            <a href="{{ route('dashboard.admin-dealers-abandoned-carts', ['admin' => auth()->id()]) }}"
                                @class([
                                    'nav-link',
                                    'active' => request()->routeIs('dashboard.admin-dealers-abandoned-carts'),
                                ])>
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Admin Dealers Abandoned Carts
                                </p>
                            </a>
                        </li> --}}
                        {{-- Representative Dealers Abandoned Carts --}}
                        {{-- <li class="nav-item">
                            <a href="{{ route('dashboard.representative-dealers-abandoned-carts', ['representative' => auth()->id()]) }}"
                                @class([
                                    'nav-link',
                                    'active' => request()->routeIs(
                                        'dashboard.representative-dealers-abandoned-carts'),
                                ])>
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Representative Dealers Abandoned Carts
                                </p>
                            </a>
                        </li> --}}

                        {{-- All Admins Dealers Abandoned Carts --}}
                        <li class="nav-item">
                            <a href="{{ route('dashboard.all-admins-dealers-abandoned-carts') }}"
                                @class([
                                    'nav-link',
                                    'active' => request()->routeIs(
                                        'dashboard.all-admins-dealers-abandoned-carts'),
                                ])>
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Admins Carts
                                </p>
                            </a>
                        </li>
                        {{-- All Representatives Dealers Abandoned Carts --}}
                        <li class="nav-item">
                            <a href="{{ route('dashboard.all-representatives-dealers-abandoned-carts') }}"
                                @class([
                                    'nav-link',
                                    'active' => request()->routeIs(
                                        'dashboard.all-representatives-dealers-abandoned-carts'),
                                ])>
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Representatives Carts
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>



                {{-- -------------------- Price Lists -------------------- --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard.price-lists.index') }}" @class([
                        'nav-link',
                        'active' => request()->routeIs('dashboard.price-lists.*'),
                    ])>
                        <i class="nav-icon fas fa-tags"></i>
                        <p>
                            Price Lists
                        </p>
                    </a>
                </li>

                {{-- -------------------- Product Catalog -------------------- --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard.catalog.index') }}" @class([
                        'nav-link',
                        'active' => request()->routeIs('dashboard.catalog.*'),
                    ])>
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Product Catalog
                        </p>
                    </a>
                </li>

                <li @class([
                    'nav-item',
                    'menu-open' => request()->routeIs('dashboard.page-breadcrumps.*'),
                ])>
                    <a href="#" @class([
                        'nav-link',
                        'active' => request()->routeIs('dashboard.page-breadcrumps.*'),
                    ])>
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Front Pages
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        {{-- Page Breadcrumps --}}
                        <li class="nav-item">
                            <a href="{{ route('dashboard.page-breadcrumps.index') }}" @class([
                                'nav-link',
                                'active' => request()->routeIs('dashboard.page-breadcrumps.*'),
                            ])>
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Page Breadcrumps
                                </p>
                            </a>
                        </li>

                        {{-- SEO Pages --}}
                        <li class="nav-item">
                            <a href="{{ route('dashboard.seo-pages.index') }}" @class([
                                'nav-link',
                                'active' => request()->routeIs('dashboard.seo-pages.*'),
                            ])>
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    SEO Pages
                                </p>
                            </a>
                        </li>
                    </ul>
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

        {{-- Signed-in admin at the bottom of the menu --}}
        <div class="adm-me">
            <span class="adm-me-avatar" aria-hidden="true">{{ strtoupper(mb_substr(auth()->user()->name ?? '?', 0, 1)) }}</span>
            <span class="adm-me-text">
                <span class="adm-me-name">{{ auth()->user()->name }}</span>
                <span class="adm-me-role">Admin</span>
            </span>
            <a class="adm-me-out" href="{{ route('logout') }}" title="Log out" aria-label="Log out">
                <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
            </a>
        </div>
    </div>
    <!-- /.sidebar -->
</aside>
