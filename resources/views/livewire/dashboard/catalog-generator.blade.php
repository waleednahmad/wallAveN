<div x-on:select-updated="
    if ($event.detail.target === 'category_id') {
        $wire.set('categoryId', $event.detail.value);
    } else if ($event.detail.target === 'subcategory_ids') {
        $wire.set('subcategoryIds', $event.detail.value);
    }
">
    <div class="modal fade" id="catalogModal" tabindex="-1" aria-labelledby="catalogModalLabel" aria-hidden="true" wire:ignore.self>
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
                            {{-- Category Filter --}}
                            <div class="col-md-6 mb-3">
                                <livewire:form.select-dropdown
                                    :items="$categories"
                                    target-model="category_id"
                                    label="Category"
                                    placeholder="All Categories"
                                    :searchable="true"
                                />
                            </div>

                            {{-- Sub Category Filter --}}
                            <div class="col-md-6 mb-3">
                                <livewire:form.select-dropdown
                                    :items="$subcategories"
                                    target-model="subcategory_ids"
                                    label="Sub Categories"
                                    placeholder="All Sub Categories"
                                    :searchable="true"
                                    :multiple="true"
                                    :key="'subcat-' . ($categoryId ?? 'none')"
                                />
                            </div>
                        </div>

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
</div>
