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

    {{-- Catalog Generation Modal --}}
    <div class="modal fade" id="catalogModal" tabindex="-1" aria-labelledby="catalogModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('dashboard.catalog.generate') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="catalogModalLabel">
                            <i class="fas fa-file-pdf mr-1"></i>
                            Catalog Settings
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {{-- Front Cover Image --}}
                            <div class="col-md-6 mb-3">
                                <label for="front_cover" class="form-label fw-bold">Front Cover Image</label>
                                <input type="file" class="form-control" id="front_cover" name="front_cover" accept="image/*">
                                <small class="text-muted">This image will appear on the first page of the catalog.</small>
                                <div class="mt-2">
                                    <img id="front_cover_preview" src="#" alt="Front Cover Preview"
                                        style="max-height: 150px; display: none;" class="img-thumbnail">
                                </div>
                            </div>

                            {{-- Back Cover Image --}}
                            <div class="col-md-6 mb-3">
                                <label for="back_cover" class="form-label fw-bold">Back Cover Image</label>
                                <input type="file" class="form-control" id="back_cover" name="back_cover" accept="image/*">
                                <small class="text-muted">This image will appear on the last page of the catalog.</small>
                                <div class="mt-2">
                                    <img id="back_cover_preview" src="#" alt="Back Cover Preview"
                                        style="max-height: 150px; display: none;" class="img-thumbnail">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- Products Per Page Layout --}}
                            <div class="col-md-6 mb-3">
                                <label for="layout" class="form-label fw-bold">Products Per Page</label>
                                <select class="form-select" id="layout" name="layout" required>
                                    <option value="2x3" selected>2 &times; 3 (6 products per page)</option>
                                    <option value="3x3">3 &times; 3 (9 products per page)</option>
                                </select>
                                <small class="text-muted">Choose how many products to display on each page.</small>
                            </div>
                        </div>

                        <div class="row">
                            {{-- Footer Text --}}
                            <div class="col-12 mb-3">
                                <label for="footer_text" class="form-label fw-bold">Footer Text</label>
                                <input type="text" class="form-control" id="footer_text" name="footer_text"
                                    value="All rights reserved &copy; {{ date('Y') }}" maxlength="500">
                                <small class="text-muted">This text will appear at the bottom of every page in the catalog.</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-download mr-1"></i>
                            Generate & Download
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
