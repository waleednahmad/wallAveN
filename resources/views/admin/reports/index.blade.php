@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => 'Reports',
        'links' => [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Reports', 'url' => '#'],
        ],
    ])
@endsection

@section('content')
    <livewire:dashboard.reports />
@endsection
