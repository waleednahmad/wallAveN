@extends('frontend.layout.app')

@section('title',  $seoContent->title && !empty($seoContent->title) ? $seoContent->title : 'About Us')
@section('meta_desc', $seoContent->meta_desc && !empty($seoContent->meta_desc) ? $seoContent->meta_desc : 'About Us')
@section('keywords', $seoContent->keywords && !empty($seoContent->keywords) ? $seoContent->keywords : 'About Us')

@section('content')
    {!! $page->content !!}
@endsection
