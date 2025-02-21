@extends('frontend.layout.app')

@section('content')
    <!-- Home1 Banner Section Start -->
    <div class="home1-banner-section mb-120">
        <div class="swiper home1-banner-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="banner-bg"
                        style="background-image: linear-gradient(180deg, rgba(0, 0, 0, 0.46) 0%, rgba(0, 0, 0, 0.46) 100%), url(assets/img/home1/home1-banner-bg1.jpg);">
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="banner-bg"
                        style="background-image: linear-gradient(180deg, rgba(0, 0, 0, 0.46) 0%, rgba(0, 0, 0, 0.46) 100%), url(assets/img/home1/home1-banner-bg2.jpg);">
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="banner-bg"
                        style="background-image: linear-gradient(180deg, rgba(0, 0, 0, 0.46) 0%, rgba(0, 0, 0, 0.46) 100%), url(assets/img/home1/home1-banner-bg3.jpg);">
                    </div>
                </div>
            </div>
        </div>
        <div class="banner-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-xxl-7 col-lg-8">
                        <div class="banner-content">
                            <h1>Art That Speaks To Your Soul </h1>
                            <p>Unlock a world of imagination with our curated collection of original artworks. From bold
                                abstracts to serene landscapes, discover pieces that inspire, captivate.</p>
                            <a href="#" class="primary-btn1 btn-hover">
                                <span>Explore Now</span>
                                <strong></strong>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pagination-area">
            <div class="swiper-pagination1"></div>
        </div>
    </div>
    <!-- Home1 Banner Section End -->

    <!-- Home1 About Section Start -->
    <div class="home1-about-section mb-120">
        <div class="container">
            <div class="row align-items-end">
                <div class="col-xl-8 wow animate fadeInLeft" data-wow-delay="200ms" data-wow-duration="1500ms">
                    <div class="about-content-wrap">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="about-img">
                                    <img src="{{ asset('assets/img/home1/about-img1.jpg') }}" alt="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="about-content">
                                    <h3>Discover Our Essence</h3>
                                    <p>At Walave, we are passionate art enthusiasts dedicated to connecting artists and
                                        collectors through dynamic and exciting auctions. Our platform celebrates the
                                        creativity and diversity of artists from around the world, providing a space
                                        where their works can be appreciated and acquired by</p>
                                    <ul>
                                        <li>
                                            <svg width="12" height="12" viewBox="0 0 12 12"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1 6.5L5 10.5L11 1.5" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            Integrity
                                        </li>
                                        <li>
                                            <svg width="12" height="12" viewBox="0 0 12 12"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1 6.5L5 10.5L11 1.5" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            Diversity
                                        </li>
                                        <li>
                                            <svg width="12" height="12" viewBox="0 0 12 12"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1 6.5L5 10.5L11 1.5" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            Accessibility
                                        </li>
                                        <li>
                                            <svg width="12" height="12" viewBox="0 0 12 12"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1 6.5L5 10.5L11 1.5" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            Support
                                        </li>
                                    </ul>
                                    <a href="#" class="learn-btn d-xl-none d-flex">Learn <br> More</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-9 col-lg-8">
                                <div class="countdown-wrap">
                                    <ul class="countdown-list">
                                        <li class="single-countdown">
                                            <div class="number">
                                                <h3 class="counter">65</h3>
                                                <strong>k</strong>
                                            </div>
                                            <span>Customers</span>
                                        </li>
                                        <li class="single-countdown">
                                            <div class="number">
                                                <h3 class="counter">1.5</h3>
                                                <strong>k</strong>
                                            </div>
                                            <span>Collections</span>
                                        </li>
                                        <li class="single-countdown">
                                            <div class="number">
                                                <h3 class="counter">800</h3>
                                            </div>
                                            <span>Auctions</span>
                                        </li>
                                        <li class="single-countdown">
                                            <div class="number">
                                                <h3 class="counter">1</h3>
                                                <strong>k</strong>
                                            </div>
                                            <span>Bidders</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 wow animate fadeInRight" data-wow-delay="200ms" data-wow-duration="1500ms">
                    <div class="about-img-wrap d-xl-block d-none">
                        <img src="{{ asset('assets/img/home1/about-img2.jpg') }}" alt="">
                        <a href="#" class="learn-btn">Learn <br> More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Home1 About Section End -->

    <!-- Home1 Artistic Section Start -->
    <div class="home1-artistic-section mb-120">
        <div class="container">
            <div class="row gy-md-5 g-4">
                <div class="col-lg-7 wow animate fadeInLeft" data-wow-delay="200ms" data-wow-duration="1500ms">
                    <div class="artistic-img">
                        <img src="{{ asset('assets/img/home1/artistic-img.png') }}" alt="">
                    </div>
                </div>
                <div class="col-lg-5 wow animate fadeInRight" data-wow-delay="200ms" data-wow-duration="1500ms">
                    <div class="artistic-content">
                        <h3>Our Artistic Endeavor</h3>
                        <p>At Walave, our mission is to revolutionize the art experience We are committed to: </p>
                        <ul>
                            <li>
                                <svg width="18" height="14" viewBox="0 0 18 14"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M16.4372 6.03907C14.0967 6.03907 11.9636 3.90787 11.9636 1.56547V0.605469H10.0436V1.56547C10.0436 3.26851 10.7905 4.86595 11.9626 6.03907H0.117188V7.95907H11.9626C10.7905 9.13219 10.0436 10.7296 10.0436 12.4327V13.3927H11.9636V12.4327C11.9636 10.0912 14.0967 7.95907 16.4372 7.95907H17.3972V6.03907H16.4372Z" />
                                </svg>
                                Empowering Artists
                            </li>
                            <li>
                                <svg width="18" height="14" viewBox="0 0 18 14"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M16.4372 6.03907C14.0967 6.03907 11.9636 3.90787 11.9636 1.56547V0.605469H10.0436V1.56547C10.0436 3.26851 10.7905 4.86595 11.9626 6.03907H0.117188V7.95907H11.9626C10.7905 9.13219 10.0436 10.7296 10.0436 12.4327V13.3927H11.9636V12.4327C11.9636 10.0912 14.0967 7.95907 16.4372 7.95907H17.3972V6.03907H16.4372Z" />
                                </svg>
                                Connecting Collectors
                            </li>
                            <li>
                                <svg width="18" height="14" viewBox="0 0 18 14"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M16.4372 6.03907C14.0967 6.03907 11.9636 3.90787 11.9636 1.56547V0.605469H10.0436V1.56547C10.0436 3.26851 10.7905 4.86595 11.9626 6.03907H0.117188V7.95907H11.9626C10.7905 9.13219 10.0436 10.7296 10.0436 12.4327V13.3927H11.9636V12.4327C11.9636 10.0912 14.0967 7.95907 16.4372 7.95907H17.3972V6.03907H16.4372Z" />
                                </svg>
                                Fostering Diversity
                            </li>
                            <li>
                                <svg width="18" height="14" viewBox="0 0 18 14"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M16.4372 6.03907C14.0967 6.03907 11.9636 3.90787 11.9636 1.56547V0.605469H10.0436V1.56547C10.0436 3.26851 10.7905 4.86595 11.9626 6.03907H0.117188V7.95907H11.9626C10.7905 9.13219 10.0436 10.7296 10.0436 12.4327V13.3927H11.9636V12.4327C11.9636 10.0912 14.0967 7.95907 16.4372 7.95907H17.3972V6.03907H16.4372Z" />
                                </svg>
                                Ensuring Integrity
                            </li>
                            <li>
                                <svg width="18" height="14" viewBox="0 0 18 14"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M16.4372 6.03907C14.0967 6.03907 11.9636 3.90787 11.9636 1.56547V0.605469H10.0436V1.56547C10.0436 3.26851 10.7905 4.86595 11.9626 6.03907H0.117188V7.95907H11.9626C10.7905 9.13219 10.0436 10.7296 10.0436 12.4327V13.3927H11.9636V12.4327C11.9636 10.0912 14.0967 7.95907 16.4372 7.95907H17.3972V6.03907H16.4372Z" />
                                </svg>
                                Building Community
                            </li>
                        </ul>
                        <p>We believe that art has the power to inspire, transform, and connect people. Our goal is to
                            bring this power to life by creating.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Home1 Artistic Section End -->

    <!-- Home1 General Art Section Start -->
    <div class="home1-general-art-slider-section mb-120">
        <div class="container">
            <div class="flex-wrap gap-3 row mb-60 align-items-center justify-content-between wow animate fadeInDown"
                data-wow-delay="200ms" data-wow-duration="1500ms">
                <div class="col-lg-8 col-md-9">
                    <div class="section-title">
                        <h3>General Artwork</h3>
                        <p>Join us for an exhilarating live auction experience where art meets excitement.</p>
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
                                        <div class="auction-card general-art">
                                            <div class="auction-card-img-wrap"
                                                style="height: 200px; overflow: hidden; border-radius: 10px;">
                                                <a href="{{ route('frontend.product', $product->handle) }}"
                                                    class="card-img">
                                                    <img src="{{ $product->image_src }}" loading="lazy"
                                                        style="object-fit: contain; width: 100%; height: 100%;"
                                                        alt="{{ $product->image_alt_text ?? $product->title }}">
                                                </a>
                                            </div>
                                            <div class="auction-card-content">
                                                <h6>
                                                    <a href="{{ route('frontend.product', $product->handle) }}">
                                                        {{ $product->title }}
                                                    </a>
                                                </h6>
                                                <ul>
                                                    <li>
                                                        <span>
                                                            Vendor :
                                                        </span>
                                                        {{ $product->vendor }}
                                                    </li>
                                                    @auth('dealer')
                                                        <li><span>Price : </span>
                                                            <strong>${{ $product->variant_price }}</strong>
                                                        </li>
                                                    @endauth
                                                </ul>
                                                <a href="{{ route('frontend.product', $product->handle) }}"
                                                    class="bid-btn btn-hover">
                                                    <span>
                                                        View Details
                                                    </span>
                                                    <strong></strong>
                                                </a>
                                            </div>
                                        </div>
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
@endsection
