@extends('admin.layout.app')
@section('content')
    <div class="container-fluid">
        <h3>All Admins and Their Dealers' Abandoned Carts</h3>
        @foreach($admins as $admin)
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <strong>{{ $admin->name }} (ID: {{ $admin->id }})</strong>
                </div>
                <div class="card-body">
                    <livewire:tables.admin-dealers-abandoned-carts-table :admin-id="$admin->id" />
                </div>
            </div>
        @endforeach
    </div>
@endsection
