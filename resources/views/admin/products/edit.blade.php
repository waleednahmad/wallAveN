@extends('admin.layout.app')

@section('page-actions')
    <a class="btn btn-secondary" href="{{ route('frontend.product', $product->slug) }}" target="_blank" rel="noopener"><i class="fas fa-external-link-alt" aria-hidden="true"></i> View in store</a>
    <a class="btn btn-secondary" href="{{ route('dashboard.products.create-variant', $product->id) }}"><i class="fas fa-layer-group" aria-hidden="true"></i> Variants</a>
@endsection

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
