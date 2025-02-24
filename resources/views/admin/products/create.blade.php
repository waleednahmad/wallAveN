@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => 'Products',
        'links' => [
            [
                'name' => 'Dashboard',
                'url' => route('dashboard'),
            ],
            [
                'name' => 'Products',
                'url' => route('dashboard.products.index'),
            ],
            [
                'name' => 'Create',
            ],
        ],
    ])
@endsection



@section('content')
            <livewire:dashboard.products.create-product-form />
@endsection
