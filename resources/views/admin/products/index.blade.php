@extends('admin.layout.app')

@section('page-actions')
    <a class="btn btn-primary" href="{{ route('dashboard.products.create') }}"><i class="fas fa-plus" aria-hidden="true"></i> Add product</a>
@endsection

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
        <div class="card-body">
            <livewire:tables.product-table />
        </div>
    </div>

    {{-- Media Ofcanvas --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="mediaOffcanvas" aria-labelledby="mediaOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="mediaOffcanvasLabel">
                Product Media
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <livewire:dashboard.products.product-media-offcanva />
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            // ============== Open Media Offcanvas ==============
            Livewire.on('openMediaOffcanvas', (event) => {
                let offcanvas = new bootstrap.Offcanvas(document.getElementById('mediaOffcanvas'));
                offcanvas.show();

                console.log(event[0]);
                Livewire.dispatch('setProductFiles', event[0]);
            });

            // ============== CLose Edit Offcanvas ==============
            Livewire.on('closeMediaOffcanvas', (event) => {
                bootstrap.Offcanvas.getInstance(document.getElementById('mediaOffcanvas')).hide();
            });

        });
    </script>
@endpush
