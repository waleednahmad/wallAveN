@extends('admin.layout.app')
@section('content')
    <div class="container-fluid">
        <h3>All Representatives and Their Dealers' Abandoned Carts</h3>
        @foreach($representatives as $rep)
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <strong>{{ $rep->name }} (ID: {{ $rep->id }})</strong>
                </div>
                <div class="card-body">
                    <livewire:tables.representative-dealers-abandoned-carts-table :representative-id="$rep->id" />
                </div>
            </div>
        @endforeach
    </div>
@endsection
