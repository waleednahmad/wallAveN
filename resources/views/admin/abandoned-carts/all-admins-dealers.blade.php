@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => 'Carts by admins',
        'links' => [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Carts by admins', 'url' => '#'],
        ],
    ])
@endsection

@section('content')
    <div class="container-fluid px-0">
        <p class="text-muted mb-3">Carts admins started for dealers, grouped by who started them.</p>
        @foreach($admins as $admin)
            <div class="card mb-4">
                <div class="card-header">
                    <strong>{{ $admin->name }} (ID: {{ $admin->id }})</strong>
                </div>
                <div class="card-body">
                    <livewire:tables.admin-dealers-abandoned-carts-table :admin-id="$admin->id" />
                </div>
            </div>
        @endforeach
    </div>
@endsection
