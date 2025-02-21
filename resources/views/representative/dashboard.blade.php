@extends('frontend.layout.app')

@section('content')
    <div class="dashboard-section mb-120">
        <div class="container">
            <div class="dashboard-wrapper">
                @include('representative.dashboard.sidebarComponent')
                @include('representative.pages.dashbordPage')
            </div>
        </div>
    </div>
@endsection
