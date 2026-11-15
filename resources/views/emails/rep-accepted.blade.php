@extends('emails.layout')
@php $site = getWebsiteTitle() ?: config('app.name'); @endphp

@section('preheader', 'You can now sign in and order for your dealers.')
@section('eyebrow', 'Account approved')
@section('heading', 'Welcome aboard, ' . ($rep->name ?: 'and welcome'))

@section('content')
    <p style="margin: 0 0 14px;">Your sales rep account with {{ $site }} is approved. Sign in with <strong style="color: #231A14;">{{ $rep->email }}</strong> and the password you chose when you applied.</p>
    <p style="margin: 0;">From your account you can pick a dealer, build their cart and place orders for them.</p>
    @include('emails.partials.button', ['url' => route('login'), 'label' => 'Sign in'])
@endsection
