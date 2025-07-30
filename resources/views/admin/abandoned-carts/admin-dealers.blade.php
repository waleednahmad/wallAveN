@extends('admin.layout.app')
@section('content')
    <div class="container-fluid">
        <h3>Admin Dealers Abandoned Carts</h3>
        <livewire:tables.admin-dealers-abandoned-carts-table :admin-id="$adminId" />
    </div>
@endsection
