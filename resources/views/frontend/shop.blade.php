@extends('frontend.layout.app')

@section('content')
    <!-- Breadcrumb section strats here -->
    <div class="breadcrumb-section2"
        style="background-image: linear-gradient(180deg, rgba(0, 0, 0, 0.28), rgba(0, 0, 0, 0.28)), url(assets/img/inner-page/breadcrumb-image2.jpg);">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 d-flex">
                    <div class="top-content style-2">
                        <ul>
                            <li>
                                <a href="index.html">
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M1.125 5.99955L5.602 1.52205C5.822 1.30255 6.178 1.30255 6.3975 1.52205L10.875 5.99955M2.25 4.87455V9.93705C2.25 10.2475 2.502 10.4995 2.8125 10.4995H4.875V8.06205C4.875 7.75155 5.127 7.49955 5.4375 7.49955H6.5625C6.873 7.49955 7.125 7.75155 7.125 8.06205V10.4995H9.1875C9.498 10.4995 9.75 10.2475 9.75 9.93705V4.87455M4.125 10.4995H8.25"
                                            stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>
                            </li>
                            <li>Art Catalog</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-content">
                        <h1>Art Catalog</h1>
                        <p>An art catalog is a curated assembly of artworks gathered by an individual, institution, or
                            group, often reflecting the collector's interests, tastes, or a specific theme.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb section ends here -->
    <!-- aution card section strats here -->
    <livewire:frontend.shop_page />

    
    <!-- aution card section ends here -->
@endsection
