@extends('emails.layout')
@php
    $place = trim(implode(', ', array_filter([$dealer->city, trim(($dealer->state ?? '') . ' ' . ($dealer->zip_code ?? ''))])));
@endphp

@section('preheader', ($dealer->company_name ?: $dealer->name) . ($place ? ' from ' . $place : '') . ' applied for a dealer account.')
@section('eyebrow', 'New dealer application')
@section('heading', $dealer->company_name ?: ($dealer->name ?: 'New applicant'))

@section('content')
    <p style="margin: 0;">A business applied for a dealer account. Approve or decline it from the Applications tab.</p>
    @include('emails.partials.details', ['rows' => [
        'Contact' => $dealer->name,
        'Email' => $dealer->email,
        'Phone' => $dealer->phone,
        'Location' => $place,
        'Business type' => $dealer->business_type,
        'Years in business' => $dealer->years_in_business,
        'Applied' => $dealer->created_at?->format('M j, Y · g:i A'),
    ]])
    @include('emails.partials.button', ['url' => route('dashboard.dealers.index', ['dealer' => $dealer->id]), 'label' => 'Review the application'])
@endsection
