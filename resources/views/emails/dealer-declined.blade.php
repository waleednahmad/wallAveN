@extends('emails.layout')
@php $site = getWebsiteTitle() ?: config('app.name'); @endphp

@section('preheader', 'An update on your dealer application with ' . $site . '.')
@section('eyebrow', 'Application update')
@section('heading', 'About your dealer application')

@section('content')
    <p style="margin: 0 0 14px;">Thank you for applying for a dealer account with {{ $site }}. After reviewing your application, we're not able to approve it at this time.</p>
    @if ($dealer->rejection_reason)
        <p style="margin: 0 0 14px; padding: 12px 14px; background: #F5EFE6; border-radius: 8px; color: #231A14;"><strong>Reason:</strong> {{ $dealer->rejection_reason }}</p>
    @endif
    <p style="margin: 0;">If anything has changed, or you think we got something wrong, reply to this email and we'll take another look.</p>
@endsection
