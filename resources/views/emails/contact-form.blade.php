@extends('emails.layout')

@section('preheader', \Illuminate\Support\Str::limit((string) $emailMessage, 90))
@section('eyebrow', 'Website message')
@section('heading', $emailSubject ?: 'New message')

@section('content')
    <p style="margin: 0 0 6px;">{{ $name }} wrote through the contact form. Reply to this email to answer them directly.</p>
    <div style="margin: 14px 0 4px; padding: 16px 18px; background: #F5EFE6; border-radius: 8px; color: #231A14; white-space: pre-line;">{{ $emailMessage }}</div>
    @include('emails.partials.details', ['rows' => [
        'Name' => $name,
        'Email' => $email,
        'Phone' => $phone,
    ]])
@endsection
