@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => $product->name ?: 'Edit product',
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
                'name' => 'Edit',
            ],
        ],
    ])
@endsection



@section('content')
    <livewire:dashboard.products.edit-product-form :product="$product" />
@endsection
