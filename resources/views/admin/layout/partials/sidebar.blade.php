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
        <!-- Sidebar Menu: grouped like the approved design (Sales, Customers, Catalog, Website, Team) -->
        @php
            // Work waiting, shown as counts next to the menu items (cached for a minute)
            $admCounts = \Illuminate\Support\Facades\Cache::remember('admin_menu_counts', 60, function () {
                return [
                    'orders' => \App\Models\Order::join('dealers', 'orders.dealer_id', '=', 'dealers.id')->where('orders.status', 'pending')->count(),
                    'applications' => \App\Models\Dealer::pendingApplication()->count(),
                    'carts' => \App\Models\CartTemp::whereNotNull('dealer_id')->whereNull('representative_id')->whereNull('admin_id')->distinct('dealer_id')->count('dealer_id'),
                ];
            });
            $admItem = function (string $route, string $pattern, string $icon, string $label, ?int $count = null, array $params = []) {
                return [
                    'url' => route($route, $params),
                    'active' => request()->routeIs($pattern),
                    'icon' => $icon,
                    'label' => $label,
                    'count' => $count,
                ];
            };
            $admMenu = [
                ['header' => null, 'items' => [
                    $admItem('dashboard', 'dashboard', 'fas fa-home', 'Overview'),
                ]],
                ['header' => 'Sales', 'items' => [
                    $admItem('dashboard.orders.index', 'dashboard.orders.*', 'fas fa-receipt', 'Orders', $admCounts['orders']),
                    $admItem('dashboard.abandoned-orders.index', 'dashboard.abandoned-orders.*', 'fas fa-shopping-cart', 'Dealer carts', $admCounts['carts']),
                    $admItem('dashboard.all-admins-dealers-abandoned-carts', 'dashboard.all-admins-dealers-abandoned-carts', 'fas fa-user-tie', 'Carts by admins'),
                    $admItem('dashboard.all-representatives-dealers-abandoned-carts', 'dashboard.all-representatives-dealers-abandoned-carts', 'fas fa-user-friends', 'Carts by sales reps'),
                ]],
                ['header' => 'Customers', 'items' => [
                    $admItem('dashboard.dealers.index', 'dashboard.dealers.*', 'fas fa-store', 'Dealers', $admCounts['applications']),
                    $admItem('dashboard.representatives.index', 'dashboard.representatives.*', 'fas fa-id-badge', 'Sales reps'),
                    $admItem('dashboard.price-lists.index', 'dashboard.price-lists.*', 'fas fa-percent', 'Price lists'),
                ]],
                ['header' => 'Catalog', 'items' => [
                    $admItem('dashboard.products.index', 'dashboard.products.*', 'fas fa-box', 'Products'),
                    $admItem('dashboard.categories.index', 'dashboard.categories.*', 'fas fa-th-large', 'Categories'),
                    $admItem('dashboard.sub-categories.index', 'dashboard.sub-categories.*', 'fas fa-stream', 'Sub categories'),
                    $admItem('dashboard.product-types.index', 'dashboard.product-types.*', 'fas fa-shapes', 'Product types'),
                    $admItem('dashboard.attributes.index', 'dashboard.attributes.*', 'fas fa-tags', 'Attributes'),
                    $admItem('dashboard.vendors.index', 'dashboard.vendors.*', 'fas fa-industry', 'Vendors'),
                    $admItem('dashboard.catalog.index', 'dashboard.catalog.*', 'fas fa-book-open', 'PDF catalogs'),
                ]],
                ['header' => 'Website', 'items' => [
                    $admItem('dashboard.page-breadcrumps.index', 'dashboard.page-breadcrumps.*', 'fas fa-image', 'Page banners'),
                    $admItem('dashboard.seo-pages.index', 'dashboard.seo-pages.*', 'fas fa-search', 'SEO'),
                    $admItem('dashboard.public-settings.index', 'dashboard.public-settings.*', 'fas fa-cog', 'Settings'),
                ]],
                ['header' => 'Team', 'items' => [
                    $admItem('dashboard.admins.index', 'dashboard.admins.*', 'fas fa-user-shield', 'Admins'),
                ]],
            ];
        @endphp
        <nav class="mt-1" aria-label="Main">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @foreach ($admMenu as $admGroup)
                    @if ($admGroup['header'])
                        <li class="nav-header adm-nav-header">{{ $admGroup['header'] }}</li>
                    @endif
                    @foreach ($admGroup['items'] as $admLink)
                        <li class="nav-item">
                            <a href="{{ $admLink['url'] }}" @class(['nav-link', 'active' => $admLink['active']]) @if ($admLink['active']) aria-current="page" @endif>
                                <i class="nav-icon {{ $admLink['icon'] }}" aria-hidden="true"></i>
                                <p>
                                    {{ $admLink['label'] }}
                                    @if ($admLink['count'])
                                        <span class="adm-nav-count" title="Waiting">{{ number_format($admLink['count']) }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                    @endforeach
                @endforeach
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
