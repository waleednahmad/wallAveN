@extends('emails.layout')

@section('preheader', $representative->name . ' applied to become a sales rep.')
@section('eyebrow', 'New sales rep application')
@section('heading', $representative->name ?: 'New applicant')

@section('content')
    <p style="margin: 0;">Someone applied to become a sales rep. Approve them from the Sales reps page, or delete the application if it's spam.</p>
    @include('emails.partials.details', ['rows' => [
        'Email' => $representative->email,
        'Phone' => $representative->phone,
        'Location' => trim(implode(', ', array_filter([$representative->city, $representative->state]))),
        'Applied' => $representative->created_at?->format('M j, Y · g:i A'),
    ]])
    @include('emails.partials.button', ['url' => route('dashboard.representatives.index'), 'label' => 'Open sales reps'])
@endsection
