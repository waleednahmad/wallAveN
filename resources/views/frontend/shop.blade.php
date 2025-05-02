@extends('frontend.layout.app')

@section('title', $seoContent->title && !empty($seoContent->title) ? $seoContent->title : 'Our Catalog')
@section('meta_desc', $seoContent->meta_desc && !empty($seoContent->meta_desc) ? $seoContent->meta_desc : 'Our Catalog')
@section('keywords', $seoContent->keywords && !empty($seoContent->keywords) ? $seoContent->keywords : 'Our Catalog')

@section('content')

    <livewire:frontend.shop_page />


    <!-- aution card section ends here -->
@endsection
