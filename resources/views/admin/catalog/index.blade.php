@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => 'Product Catalog',
        'links' => [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Product Catalog', 'url' => '#'],
        ],
    ])
@endsection

@section('content')
    <livewire:dashboard.catalog-generator />
@endsection
