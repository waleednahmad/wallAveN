@extends('frontend.layout.app')

@section('title', $seoContent->title && !empty($seoContent->title) ? $seoContent->title : 'Contact Us')
@section('meta_desc', $seoContent->meta_desc && !empty($seoContent->meta_desc) ? $seoContent->meta_desc : 'Contact Us')
@section('keywords', $seoContent->keywords && !empty($seoContent->keywords) ? $seoContent->keywords : 'Contact Us')

@push('styles')
    <style>
        span.error-handler {
            position: absolute;
            bottom: -20px;
            left: 5ch;
            font-size: 14px;
            color: #ff0000;
        }

        .top-area {
            background-color: #f8f9fa;
            padding: 20px 0;
        }

        .top-area .top-content ul {
            list-style-type: none;
            padding: 0;
        }

        .top-area .top-content ul li {
            display: inline-block;
            margin-right: 10px;
        }

        .top-area .top-content ul li a {
            text-decoration: none;
            color: #000;
        }
    </style>
@endpush

@section('content')

    <!-- Top area strats -->
    <div class="top-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 d-flex mb-120">
                    <div class="top-content">
                        <ul>
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
                            <li>Contact</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Top area strats -->
    <!-- contact page section strats here -->
    <livewire:frontend.contact-form />
    <!-- contact page section ends here -->

@endsection
