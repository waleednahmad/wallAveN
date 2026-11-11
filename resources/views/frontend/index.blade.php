@extends('frontend.layout.app')

@section('title', $seoContent->title && !empty($seoContent->title) ? $seoContent->title : 'Home')
@section('meta_desc', $seoContent->meta_desc && !empty($seoContent->meta_desc) ? $seoContent->meta_desc : 'Home')
@section('keywords', $seoContent->keywords && !empty($seoContent->keywords) ? $seoContent->keywords : 'Home')

@section('content')
    @php
        // Homepage content lives in code (the old pages-table HTML was leftover template text).
        $isSignedIn = auth('dealer')->check() || auth('representative')->check() || auth('web')->check();
        $canSeeShop = showCategoryAndShopPages() || $isSignedIn;

        $heroProduct = $products->first();
        $rugsCategory = $categories->firstWhere('slug', 'rugs');
        $otherCategories = $categories->reject(fn ($c) => in_array($c->slug, ['rugs', 'new-arrivals']))->take(4);

        $heroMain = $heroProduct?->image ? asset($heroProduct->image) : asset('assets/img/home1/about-img1.webp');
        $heroInset = $rugsCategory?->image ? asset($rugsCategory->image) : asset('assets/img/home1/feature-img.webp');

        $productCount = \Illuminate\Support\Facades\Cache::remember('home_active_product_count', 3600, fn () => \App\Models\Product::active()->count());
        $productCountLabel = $productCount >= 20 ? (floor($productCount / 10) * 10) : $productCount;

        $sizes = [
            ['w' => 2, 'h' => 8, 'label' => 'Runner', 'box' => [34, 136]],
            ['w' => 5, 'h' => 8, 'label' => 'Medium', 'box' => [85, 136]],
            ['w' => 8, 'h' => 11, 'label' => 'Large', 'box' => [116, 160]],
            ['w' => 9, 'h' => 12, 'label' => 'Room-size', 'box' => [132, 176]],
        ];
    @endphp

    {{-- ===================== Hero ===================== --}}
    <div class="gr-container">
        <section class="gr-hero">
            <div class="gr-hero-text gr-rise gr-d1">
                <p class="gr-eyebrow">Wholesale since 2015 · Chicago</p>
                <h1 class="gr-display">Woven for the <em>showroom</em> floor.</h1>
                <p class="gr-lead" style="max-width: 520px; margin-bottom: 36px;">
                    Hundreds of in-stock rugs, furniture and décor pieces — sourced for retailers, priced for trade, ready from our Chicago warehouse.
                </p>
                <div class="d-flex flex-wrap gap-3" style="margin-bottom: 40px;">
                    @if ($canSeeShop)
                        <a href="{{ route('frontend.shop') }}" class="gr-btn gr-btn-dark">Explore the catalog <span aria-hidden="true">→</span></a>
                    @else
                        <a href="{{ route('login') }}" class="gr-btn gr-btn-dark">Dealer login <span aria-hidden="true">→</span></a>
                    @endif
                    @unless ($isSignedIn)
                        <a href="{{ route('frontend.register') }}" class="gr-btn gr-btn-outline">Open a dealer account</a>
                    @endunless
                </div>
                <ul class="gr-checks">
                    <li>Runners to room-size</li>
                    <li>Order online 24/7</li>
                    <li>Rugs, furniture &amp; décor</li>
                </ul>
            </div>
            <div class="gr-hero-media">
                <div class="gr-hero-arch gr-rise gr-d2"><img src="{{ $heroMain }}" alt="{{ $heroProduct?->name ?? 'Golden Rugs' }}"></div>
                <div class="gr-hero-inset gr-rise gr-d3"><img src="{{ $heroInset }}" alt="Rug styled in a living room"></div>
                @if ($heroProduct)
                    <div class="gr-hero-tag gr-rise gr-d4">
                        <p style="font-size: 12px; letter-spacing: .12em; text-transform: uppercase; color: var(--gr-madder); font-weight: 600;">Just arrived</p>
                        <p style="font-family: var(--gr-font-display); font-size: 20px; margin: 2px 0 4px;">{{ $heroProduct->name }}</p>
                        <p style="font-size: 13px; color: var(--gr-muted);">New in the catalog</p>
                    </div>
                @endif
            </div>
        </section>
    </div>

    {{-- ===================== Numbers ===================== --}}
    <div class="gr-kilim" aria-hidden="true"></div>
    <section class="gr-stats">
        <div class="gr-container">
            <div class="gr-reveal">
                <p class="gr-stat-num" aria-label="Since 2015"><span class="gr-count" aria-hidden="true" style="--to: 2015"></span></p>
                <p class="gr-stat-label">Supplying retailers for over a decade</p>
            </div>
            <div class="gr-reveal">
                <p class="gr-stat-num" aria-label="{{ $productCountLabel }} plus"><span class="gr-count" aria-hidden="true" style="--to: {{ $productCountLabel }}"></span><span aria-hidden="true">+</span></p>
                <p class="gr-stat-label">Rugs, furniture and décor designs</p>
            </div>
            <div class="gr-reveal">
                <p class="gr-stat-num" aria-label="{{ $categories->count() }}"><span class="gr-count" aria-hidden="true" style="--to: {{ $categories->count() }}"></span></p>
                <p class="gr-stat-label">Categories to fill a whole showroom</p>
            </div>
            <div class="gr-reveal">
                <p class="gr-stat-num" aria-label="7 days"><span class="gr-count" aria-hidden="true" style="--to: 7"></span><span aria-hidden="true"> days</span></p>
                <p class="gr-stat-label">A week our team picks up the phone</p>
            </div>
        </div>
    </section>

    @if ($canSeeShop)
        {{-- ===================== Categories ===================== --}}
        <section class="gr-section" style="padding-bottom: 48px;">
            <div class="gr-container">
                <div class="gr-head-row gr-reveal">
                    <h2 class="gr-h2">Everything for the<br><em>whole room.</em></h2>
                    <a href="{{ route('frontend.shop') }}" class="gr-link">Browse all categories →</a>
                </div>
                <div class="gr-bento">
                    @if ($rugsCategory)
                        <a href="{{ route('frontend.shop', ['category' => $rugsCategory->slug]) }}" class="is-feature gr-zoom gr-reveal">
                            <img src="{{ asset($rugsCategory->image) }}" alt="{{ $rugsCategory->name }}" loading="lazy">
                            <span class="gr-plate">{{ $rugsCategory->name }} <span aria-hidden="true">→</span></span>
                        </a>
                    @endif
                    @foreach ($otherCategories as $category)
                        <a href="{{ route('frontend.shop', ['category' => $category->slug]) }}" class="gr-zoom gr-reveal">
                            <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" loading="lazy">
                            <span class="gr-plate">{{ $category->name }} <span aria-hidden="true">→</span></span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ===================== Shop by size ===================== --}}
        <section style="padding: 32px 0;">
            <div class="gr-container">
                <div class="gr-card gr-reveal d-flex flex-wrap align-items-center" style="padding: clamp(24px, 4vw, 48px); gap: 48px;">
                    <div style="flex: 1 1 300px; min-width: 0;">
                        <p class="gr-eyebrow no-rule">Shop rugs by size</p>
                        <h2 class="gr-h2" style="font-size: clamp(28px, 3vw, 40px); margin-bottom: 12px;">Stock the sizes your customers ask for.</h2>
                        <p style="color: var(--gr-muted); margin: 0;">Most designs come in several sizes. Pick one to see every rug available in it.</p>
                    </div>
                    <div class="d-flex flex-wrap align-items-end" style="flex: 2 1 520px; gap: 24px;">
                        @foreach ($sizes as $s)
                            <a href="{{ route('frontend.shop', ['category' => 'rugs', 'size' => $s['w'] . 'x' . $s['h']]) }}" class="d-flex flex-column align-items-center text-decoration-none" style="gap: 10px; color: var(--gr-ink);">
                                <span aria-hidden="true" style="width: {{ $s['box'][0] }}px; height: {{ $s['box'][1] }}px; border: 2px solid var(--gr-ink); border-radius: 4px; background: var(--gr-linen);"></span>
                                <span style="font-weight: 600;">{{ $s['w'] }}' × {{ $s['h'] }}'</span>
                                <span style="font-size: 13px; color: var(--gr-muted); margin-top: -6px;">{{ $s['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== New arrivals ===================== --}}
        <section class="gr-section" style="padding-top: 56px;">
            <div class="gr-container">
                <div class="gr-head-row gr-reveal">
                    <div>
                        <h2 class="gr-h2">New arrivals</h2>
                        <p style="color: var(--gr-muted); margin: 8px 0 0; font-size: 17px;">The latest additions to our catalog.</p>
                    </div>
                    <a href="{{ route('frontend.shop', ['new_arrivals' => 1]) }}" class="gr-link">See all new arrivals →</a>
                </div>
                <div class="row g-4">
                    @foreach ($products->take(8) as $product)
                        <div class="col-xl-3 col-lg-4 col-sm-6 gr-reveal">
                            <livewire:frontend.components.product-card-component :product="$product" :key="'home-' . $product->id" />
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @unless ($isSignedIn)
        {{-- ===================== Become a dealer ===================== --}}
        <section class="gr-steps">
            <div class="gr-container gr-section">
                <div class="gr-head-row" style="margin-bottom: 48px;">
                    <h2 class="gr-h2" style="max-width: 640px;">Become a Golden Rugs dealer in three steps.</h2>
                    <a href="{{ route('frontend.register') }}" class="gr-btn gr-btn-light">Start your application</a>
                </div>
                <div class="row g-4">
                    <div class="col-md-4 gr-reveal"><div class="gr-step"><p class="gr-step-num">01</p><h3>Apply online</h3><p>Tell us about your store. It takes about five minutes.</p></div></div>
                    <div class="col-md-4 gr-reveal"><div class="gr-step"><p class="gr-step-num">02</p><h3>Get approved</h3><p>Our team reviews your application and sets up your trade price list.</p></div></div>
                    <div class="col-md-4 gr-reveal"><div class="gr-step"><p class="gr-step-num">03</p><h3>Order at trade prices</h3><p>Browse, add to cart and track every order from your dashboard.</p></div></div>
                </div>
            </div>
        </section>
    @endunless

    {{-- ===================== Why Golden Rugs ===================== --}}
    <section class="gr-section">
        <div class="gr-container">
            <h2 class="gr-h2 gr-reveal" style="margin-bottom: 40px;">Why retailers choose Golden Rugs</h2>
            <div class="row g-4">
                <div class="col-md-4 gr-reveal"><div class="gr-card gr-feature-card"><i class="bi bi-shield-check" aria-hidden="true"></i><h3>Quality you can stand behind</h3><p>Machine-made and hand-tufted rugs selected for durability, plus furniture built for everyday use.</p></div></div>
                <div class="col-md-4 gr-reveal"><div class="gr-card gr-feature-card"><i class="bi bi-grid" aria-hidden="true"></i><h3>Variety in every size</h3><p>Most rug designs come in several sizes and colorways, so you can stock what your customers ask for.</p></div></div>
                <div class="col-md-4 gr-reveal"><div class="gr-card gr-feature-card"><i class="bi bi-telephone" aria-hidden="true"></i><h3>A partner, not just a supplier</h3><p>Order online any time and reach our team by phone seven days a week.</p></div></div>
            </div>
        </div>
    </section>

    {{-- ===================== Visit / call ===================== --}}
    <section class="gr-section gr-visit" style="padding-top: 0;">
        <div class="gr-container d-flex flex-wrap align-items-center" style="gap: 56px;">
            <img class="gr-reveal" src="{{ asset('assets/img/home1/about-img1.webp') }}" alt="Golden Rugs showroom" loading="lazy" style="flex: 1 1 460px; min-width: 0; width: 100%; height: 460px; object-fit: cover; border-radius: 16px;">
            <div class="gr-reveal" style="flex: 1 1 420px; min-width: 0;">
                <p class="gr-eyebrow no-rule">Visit or call</p>
                <h2 class="gr-h2" style="font-size: clamp(30px, 3.4vw, 44px); margin-bottom: 20px;">See the rugs in person at our Chicago warehouse.</h2>
                <dl>
                    <div><dt>Phone</dt><dd><a href="tel:+17734903801">(773) 490-3801</a></dd></div>
                    <div><dt>Location</dt><dd>Chicago, IL</dd></div>
                    <div><dt>Mon – Sat</dt><dd>9:00 am – 7:30 pm</dd></div>
                    <div><dt>Sunday</dt><dd>9:30 am – 5:30 pm</dd></div>
                </dl>
                <a href="{{ route('frontend.contactUs') }}" class="gr-btn gr-btn-outline">Contact &amp; directions</a>
            </div>
        </div>
    </section>
@endsection
