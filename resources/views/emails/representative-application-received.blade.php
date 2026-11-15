@extends('emails.layout')
@php $site = getWebsiteTitle() ?: config('app.name'); @endphp

@section('preheader', 'Thanks for applying to represent ' . $site . '.')
@section('eyebrow', 'Application received')
@section('heading', 'Thanks for applying' . ($representative?->name ? ', ' . $representative->name : ''))

@section('content')
    <p style="margin: 0 0 14px;">We received your application to become a sales rep for {{ $site }}. Our team will review it and get back to you, usually within a few business days.</p>
    <p style="margin: 0;">Questions in the meantime? Just reply to this email{{ companyPhone() ? ' or call ' . companyPhone() : '' }}.</p>
@endsection
