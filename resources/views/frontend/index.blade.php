@extends('frontend.layout.app')

@section('content')
    {!! $page->content !!}

    @if (showCategoryAndShopPages())
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
