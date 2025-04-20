@extends('frontend.layout.app')

@section('title', 'Home')

@section('content')
    {!! $page->content !!}
    {{-- Categories Section Grid 6 columns --}}
    <div class="home1-category-section mb-120">
        <div class="container">
            <div class="flex-wrap gap-3 row mb-3 align-items-center justify-content-between wow animate fadeInDown"
                data-wow-delay="200ms" data-wow-duration="1500ms">
                <div class="col-12">
                    <div class="section-title">
                        <h3>Categories</h3>
                        <p>
                            Explore our diverse range of categories, each offering a unique selection of high-quality
                            home décor products. Find the perfect items to suit your style and needs.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                @forelse ($categories as $category)
                    <div class="col-lg-2 col-md-4 col-sm-6 wow animate fadeInUp" data-wow-delay="200ms"
                        data-wow-duration="1500ms">
                        <a class="card text-center p-2 category-card"
                            href="{{ route('frontend.shop', ['category' => $category->slug]) }}"
                            style="background-image: url({{ asset($category->image) }}) ; background-size: cover;">
                            <div class="card-body">
                                <h5 class="m-0">
                                    {{ $category->name }}
                                </h5>
                            </div>
                        </a>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>


    @if (showCategoryAndShopPages() || auth('dealer')->check() || auth('representative')->check() || auth('web')->check())
        <!-- Home1 General Art Section Start -->
        <div class="home1-general-art-slider-section mb-120">
            <div class="container">
                <div class="flex-wrap gap-3 row mb-60 align-items-center justify-content-between wow animate fadeInDown"
                    data-wow-delay="200ms" data-wow-duration="1500ms">
                    <div class="col-lg-8 col-md-9">
                        <div class="section-title">
                            <h3>New Arrivals</h3>
                            <p>
                                Discover our newest wholesale offerings, featuring fresh styles and designs to meet the
                                needs
                                of your customers. Stay ahead of trends with our latest collection of high-quality home
                                décor.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 d-flex justify-content-md-end">
                        <a href="{{ route('frontend.shop') }}" class="view-all-btn">View All</a>
                    </div>
                </div>
                <div class="general-art-slider-wrap wow animate fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="swiper home1-generat-art-slider">
                                <div class="swiper-wrapper">
                                    @foreach ($products as $product)
                                        <div class="swiper-slide">
                                            <livewire:frontend.components.product-card-component :product="$product"
                                                :key="$product->id" />
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slider-btn-grp">
                        <div class="slider-btn generat-art-slider-prev">
                            <svg width="10" height="16" viewBox="0 0 10 16" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.735295 8.27932L10 16L4.10428 8.27932L10 0.558823L0.735295 8.27932Z" />
                            </svg>
                        </div>
                        <div class="slider-btn generat-art-slider-next">
                            <svg width="10" height="16" viewBox="0 0 10 16" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.26471 7.72068L0 0L5.89572 7.72068L0 15.4412L9.26471 7.72068Z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Home1 General Art Section End -->
    @endif
@endsection
