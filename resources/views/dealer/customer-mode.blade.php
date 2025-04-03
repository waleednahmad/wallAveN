@extends('frontend.layout.app')

@section('content')
    <div class="dashboard-section mb-120">
        <div class="container">
            <div class="dashboard-wrapper">
                <livewire:frontend.dealer.dashboard.sidebar-component />
                @include('dealer.pages.customerModePage')
            </div>
        </div>
    </div>
@endsection
