@php
    $canSeeShop = showCategoryAndShopPages() || auth('dealer')->check() || auth('representative')->check() || auth('web')->check();
@endphp
<footer class="footer-section">
    <div class="gr-kilim" aria-hidden="true"></div>
    <div class="container" style="padding-top: 56px;">
        <div class="footer-menu-wrap">
            <div class="row gy-5">
                <div class="col-lg-4 col-12">
                    <div class="footer-content-area">
                        <a href="{{ route('frontend.home') }}" class="footer-logo">
                            <img alt="Golden Rugs" class="img-fluid dark" src="{{ getMainImage() }}" style="width: 150px;">
                        </a>
                        <p>
                            Since 2015, Golden Rugs has been a trusted wholesale supplier of premium rugs and curated
                            furniture for retailers and businesses across the United States.
                        </p>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-6">
                    <div class="footer-widget">
                        <div class="widget-title">
                            <h5>Company</h5>
                        </div>
                        <ul class="widget-list">
                            <li><a href="{{ route('frontend.home') }}">Home</a></li>
                            <li><a href="{{ route('frontend.aboutUs') }}">About Us</a></li>
                            <li><a href="{{ route('frontend.contactUs') }}">Contact Us</a></li>
                            @guest('dealer')
                                @guest('representative')
                                    @guest('web')
                                        <li><a href="{{ route('frontend.register') }}">Become a Dealer</a></li>
                                        <li><a href="{{ route('login') }}">Dealer Login</a></li>
                                    @endguest
                                @endguest
                            @endguest
                        </ul>
                    </div>
                </div>

                @if ($canSeeShop)
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="footer-widget">
                            <div class="widget-title">
                                <h5>Shop</h5>
                            </div>
                            <ul class="widget-list">
                                <li><a href="{{ route('frontend.shop', ['new_arrivals' => 1]) }}">New Arrivals</a></li>
                                @foreach ($publicActiveCategories ?? [] as $category)
                                    <li>
                                        <a href="{{ route('frontend.shop', ['category' => $category->slug]) }}">{{ $category->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="col-lg-4 col-md-4 col-12">
                    <div class="footer-widget">
                        <div class="widget-title">
                            <h5>Contact</h5>
                        </div>
                        <ul class="widget-list">
                            <li><a href="tel:+17734903801">(773) 490-3801</a></li>
                            <li>Mon – Sat: 9:00 am – 7:30 pm</li>
                            <li>Sun: 9:30 am – 5:30 pm</li>
                            <li><a href="{{ route('frontend.contactUs') }}">Send us a message</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="copyright-area">
                <p>
                    &copy; {{ date('Y') }} <a href="{{ route('frontend.home') }}">Golden Rugs</a>.
                    All rights reserved. | Designed &amp; Developed by
                    <a href="https://zenith-tech.digital" target="_blank" rel="noopener">Zenith Tech</a>
                </p>
            </div>
        </div>
    </div>
</footer>
