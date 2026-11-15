@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => 'Carts by sales reps',
        'links' => [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Carts by sales reps', 'url' => '#'],
        ],
    ])
@endsection

@section('content')
    <div class="container-fluid px-0">
        <p class="text-muted mb-3">Carts sales reps started for dealers, grouped by who started them.</p>
        @foreach($representatives as $rep)
            <div class="card mb-4">
                <div class="card-header">
                    <strong>{{ $rep->name }} (ID: {{ $rep->id }})</strong>
                </div>
                <div class="card-body">
                    <livewire:tables.representative-dealers-abandoned-carts-table :representative-id="$rep->id" />
                </div>
            </div>
        @endforeach
    </div>
@endsection
