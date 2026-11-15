@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => 'PDF catalogs',
        'links' => [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'PDF catalogs', 'url' => '#'],
        ],
    ])
@endsection

@section('content')
    <livewire:dashboard.catalog-generator />
@endsection
