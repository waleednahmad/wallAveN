@extends('frontend.layout.app')

@section('title', $seoContent->title && !empty($seoContent->title) ? $seoContent->title : 'Contact Us')
@section('meta_desc', $seoContent->meta_desc && !empty($seoContent->meta_desc) ? $seoContent->meta_desc : 'Contact Us')
@section('keywords', $seoContent->keywords && !empty($seoContent->keywords) ? $seoContent->keywords : 'Contact Us')


@section('content')
    {!! $page->content !!}
@endsection
