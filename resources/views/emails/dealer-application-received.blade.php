@extends('emails.layout')
@php $site = getWebsiteTitle() ?: config('app.name'); @endphp

@section('preheader', 'Thanks for applying. Here is what happens next.')
@section('eyebrow', 'Application received')
@section('heading', 'Thanks for applying' . ($dealer?->company_name ? ', ' . $dealer->company_name : ''))

@section('content')
    <p style="margin: 0 0 14px;">We received your dealer application for {{ $site }}. Our team reviews every application by hand, usually within one to two business days.</p>
    <p style="margin: 0 0 6px; color: #231A14; font-weight: bold;">What happens next</p>
    <p style="margin: 0 0 4px;">1. We check your business details and resale certificate.</p>
    <p style="margin: 0 0 4px;">2. Once approved, you get an email and can sign in with the email and password you chose.</p>
    <p style="margin: 0 0 14px;">3. You see your trade prices and can order online any time.</p>
    <p style="margin: 0;">Questions in the meantime? Just reply to this email{{ companyPhone() ? ' or call ' . companyPhone() : '' }}.</p>
@endsection
