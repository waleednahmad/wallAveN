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
                'url' => '#',
            ],
        ],
    ])
@endsection



@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <a class="btn btn-primary" href="{{ route('dashboard.products.create') }}">
                    <i class="fas fa-plus"></i>
                    Add new product
                </a>
            </div>
        </div>
        <div class="card-body">
            <livewire:tables.product-table />
        </div>
    </div>
@endsection
