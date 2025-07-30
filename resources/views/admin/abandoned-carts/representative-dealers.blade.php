@extends('admin.layout.app')
@section('content')
    <div class="container-fluid">
        <h3>Representative Dealers Abandoned Carts</h3>
        <livewire:tables.representative-dealers-abandoned-carts-table :representative-id="$representativeId" />
    </div>
@endsection
