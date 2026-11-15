@extends('emails.layout')
@php $site = getWebsiteTitle() ?: config('app.name'); @endphp

@section('preheader', 'You can now sign in, see your trade prices and order online.')
@section('eyebrow', 'Account approved')
@section('heading', 'Welcome to ' . $site . ($dealer->company_name ? ', ' . $dealer->company_name : ''))

@section('content')
    <p style="margin: 0 0 14px;">Good news: your dealer account is approved. Sign in with <strong style="color: #231A14;">{{ $dealer->email }}</strong> and the password you chose when you applied.</p>
    <p style="margin: 0;">Once you're in you can browse the full catalog with your trade prices, order online any time and follow every order from your dashboard.</p>
    @include('emails.partials.button', ['url' => route('login'), 'label' => 'Sign in and start ordering'])
    <p style="margin: 14px 0 0; font-size: 13px;">Forgot your password? Use “Forgot password?” on the sign-in page.</p>
@endsection
