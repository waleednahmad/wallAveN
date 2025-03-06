@extends('frontend.layout.app')

@push('styles')
    <style>
        .values-container {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 10px !important;
            margin-top: 10px !important;
        }

        .values-container span {
            padding: 5px 10px !important;
            background-color: #f5f5f5 !important;
            border-radius: 5px !important;
            cursor: pointer !important;
        }

        .values-container span:hover {
            background-color: #e0e0e0 !important;
        }

        .values-container span.active {
            border: 1px solid #000 !important;
        }
    </style>
@endpush

@section('content')
    <!-- Top area strats -->
    <div class="top-area">
        <div class="container">
            <div class="row">
                <div class="mb-5 col-lg-12 d-flex">
                    <div class="top-content style-3 w-100">
                        <ul class="w-100">
                            <li>
                                <a href="{{ route('frontend.home') }}">
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M1.125 5.99955L5.602 1.52205C5.822 1.30255 6.178 1.30255 6.3975 1.52205L10.875 5.99955M2.25 4.87455V9.93705C2.25 10.2475 2.502 10.4995 2.8125 10.4995H4.875V8.06205C4.875 7.75155 5.127 7.49955 5.4375 7.49955H6.5625C6.873 7.49955 7.125 7.75155 7.125 8.06205V10.4995H9.1875C9.498 10.4995 9.75 10.2475 9.75 9.93705V4.87455M4.125 10.4995H8.25"
                                            stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.shop') }}">
                                    Shop
                                </a>
                            </li>
                            {{-- <li>
                                <a href="#">
                                    {{ $product->categories->first()->name }}
                                </a>
                            </li> --}}
                            <li>
                                {{ $product->name }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Top area ends -->
    <!-- Auction details section strats here -->
    <livewire:frontend.product-page :product="$product" />
    <!-- Auction details section ends here -->
@endsection
