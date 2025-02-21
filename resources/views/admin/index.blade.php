@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => ' Welcome, ' . auth()->user()->name,
        'links' => [
            [
                'name' => 'Dashboard',
                'url' => route('dashboard'),
            ],
        ],
    ])
@endsection

@section('content')
    {{-- ============= KPIS ============= --}}
    <div class="row">

        

    </div>
@endsection
