{{-- Admin top bar: menu toggle, global search (orders, dealers, products), View store --}}
<nav class="main-header navbar navbar-expand navbar-white navbar-light adm-topbar">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link adm-icon-link" data-widget="pushmenu" href="#" role="button" aria-label="Toggle menu"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <div class="adm-topbar-search">
        <livewire:dashboard.global-search />
    </div>

    <ul class="ml-auto navbar-nav adm-topbar-right">
        <li class="nav-item">
            <a href="{{ route('frontend.home') }}" class="btn btn-secondary btn-sm adm-viewstore" target="_blank" rel="noopener" aria-label="View store" title="View store">
                <i class="fas fa-external-link-alt" aria-hidden="true"></i> <span class="adm-viewstore-text">View store</span>
            </a>
        </li>
    </ul>
</nav>
