@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => 'Product Catalog',
        'links' => [
            [
                'name' => 'Dashboard',
                'url' => route('dashboard'),
            ],
            [
                'name' => 'Product Catalog',
                'url' => '#',
            ],
        ],
    ])
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Generate Product Catalog</h3>
        </div>
        <div class="card-body">
            <p>Generate a PDF catalog of all active products. Choose the layout and optional cover images before downloading.</p>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#catalogModal">
                <i class="fas fa-file-pdf mr-1"></i>
                Generate Catalog
            </button>
        </div>
    </div>

    <livewire:dashboard.catalog-generator />
@endsection

@push('scripts')
<script>
    // Image preview for front cover
    document.getElementById('front_cover').addEventListener('change', function(e) {
        const preview = document.getElementById('front_cover_preview');
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.src = event.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(e.target.files[0]);
        } else {
            preview.style.display = 'none';
        }
    });

    // Image preview for back cover
    document.getElementById('back_cover').addEventListener('change', function(e) {
        const preview = document.getElementById('back_cover_preview');
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.src = event.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(e.target.files[0]);
        } else {
            preview.style.display = 'none';
        }
    });
</script>
@endpush
