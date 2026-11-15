<form wire:submit="save">
    <div class="row">
        {{-- -------------------- Main Product Info -------------------- --}}
        <div class="col-md-6">
            <div class="card">
                {{-- Name --}}
                <div class="card-header">
                    <h6>
                        Main Product Info
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- Name --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name
                                    <span class="text-danger">
                                        *
                                    </span>
                                </label>
                                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name')]) id="name" name="name"
                                    required value="{{ old('name') }}" wire:model="name">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- SKU --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sku">SKU
                                    <span class="text-danger">
                                        *
                                    </span>
                                </label>
                                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('sku')]) id="sku" name="sku"
                                    required value="{{ old('sku') }}" wire:model="sku">
                                @error('sku')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>


                        {{-- Status --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status
                                    <span class="text-danger">
                                        *
                                    </span>
                                </label>
                                <select @class(['form-control', 'is-invalid' => $errors->has('status')]) id="status" name="status" required
                                    wire:model="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Vendor --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="vendor_id">Vendor
                                </label>
                                <select @class(['form-control', 'is-invalid' => $errors->has('vendor_id')]) id="vendor_id" name="vendor_id"
                                    wire:model="vendor_id">
                                    <option value="">Select Vendor</option>
                                    @foreach ($this->vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                    @endforeach
                                </select>
                                @error('vendor_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Decsription --}}
                        <div class="col-md-12">
                            <div class="form-group" wire:ignore>
                                <label for="description">
                                    Description
                                </label>
                                <textarea id="description" @class(['form-control', 'is-invalid' => $errors->has('description')]) name="description" wire:model="description"></textarea>
                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        {{-- =============== --}}
        {{-- RIGHT SIDE --}}
        {{-- =============== --}}
        <div class="col-md-6">
            {{-- -------------------- Main Product Categories -------------------- --}}
            <div class="card">
                {{-- Name --}}
                <div class="card-header">
                    <h6>
                        Product Categories
                    </h6>
                </div>
                <div class="card-body">

                    {{-- Main Categories --}}
                    <h5 class="d-flex align-items-center justify-content-between">
                        <span>
                            Categories
                            <small>
                                ({{ count($this->selectedCategories) . '/' . $this->categories->count() }})
                            </small>
                        </span>

                        {{-- seach category --}}
                        <input type="text" wire:model.live="searchCategory" class="form-control"
                            placeholder="Search Category" style="width: 200px;">
                    </h5>
                    <div class="category-container">
                        @forelse ($this->categories as $item)
                            <div wire:click="toggleCategory({{ $item->id }})" @class([
                                'category-card ',
                                'active' => in_array($item->id, $this->selectedCategories),
                            ])>
                                <p>
                                    {{ $item->name }}
                                </p>
                            </div>
                        @empty
                        @endforelse
                    </div>

                    @if (isset($this->subCategories))
                        <hr>
                        {{-- Sub Categories --}}
                        <h5 class="d-flex align-items-center justify-content-between">
                            <span>
                                Sub Categories
                                <small>
                                    ({{ count($this->selectedSubCategories) . '/' . $this->subCategories->count() }})
                                </small>
                            </span>
                            {{-- seach category --}}
                            <input type="text" wire:model.live="searchSubCategory" class="form-control"
                                placeholder="Search Sub Category" style="width: 200px;">
                        </h5>
                        <div class="category-container w-100">
                            @forelse ($this->subCategories as $item)
                                <div wire:click="toggleSubCategory({{ $item->id }})" @class([
                                    'category-card ',
                                    'active' => in_array($item->id, $this->selectedSubCategories),
                                ])>
                                    <p>
                                        {{ $item->name }}
                                    </p>
                                </div>
                            @empty
                                <div class="adm-hint">
                                    No sub categories for the selected categories yet.
                                </div>
                            @endforelse
                        </div>
                    @endif

                    @if (isset($this->productTypes))
                        <hr>
                        {{-- Product Types --}}
                        <h5 class="d-flex align-items-center justify-content-between">
                            <span>
                                Product Types
                                <small>
                                    ({{ count($this->selectedProductTypes) . '/' . $this->productTypes->count() }})
                                </small>
                            </span>
                            {{-- seach category --}}
                            <input type="text" wire:model.live="searchProductType" class="form-control"
                                placeholder="Search Product Type" style="width: 200px;">
                        </h5>
                        <div class="category-container w-100">
                            @forelse ($this->productTypes as $item)
                                <div wire:click="toggleProductType({{ $item->id }})" @class([
                                    'category-card ',
                                    'active' => in_array($item->id, $this->selectedProductTypes),
                                ])>
                                    <p>
                                        {{ $item->name }}
                                    </p>
                                </div>
                            @empty
                                <div class="adm-hint">
                                    No product types for the selected sub categories yet.
                                </div>
                            @endforelse
                        </div>
                    @endif
                </div>
            </div>
            {{-- -------------------- Main Product Attributes -------------------- --}}
            <div class="card">
                {{-- Name --}}
                <div class="card-header">
                    <h6>
                        Product Attributes
                    </h6>
                </div>
                <div class="card-body">
                    {{-- Main Categories --}}
                    <h5 class="d-flex align-items-center justify-content-between">
                        <span>
                            Attributes
                            <small>
                                ({{ count($this->selectedAttributes) . '/' . $this->productAttributes->count() }})
                            </small>
                        </span>
                    </h5>
                    <div class="category-container">
                        @forelse ($this->productAttributes as $attribute)
                            <div wire:click="toggleAttribute({{ $attribute->id }})" @class([
                                'category-card ',
                                'active' => in_array($attribute->id, $this->selectedAttributes),
                            ])>
                                <p>
                                    {{ $attribute->name }}
                                </p>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12" x-data="{ uploading: false, progress: 0, isUploading: false, isUploadingStart: false, errMessage: '', isError: false }"
            x-on:livewire-upload-start="isUploading = true; isUploadingStart = true"
            x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-cancel="isUploading = false"
            x-on:livewire-upload-error="isUploading = false; errMessage = $event.detail.message; isError = true"
            x-on:livewire-upload-progress="progress = $event.detail.progress">
            <div class="card">
                <div class="card-header">
                    <h6>
                        Product Images
                    </h6>
                </div>
                <div class="card-body">
                    {{-- Photos: upload, drag to reorder (the first photo is the main one), view, delete --}}
                    <div class="col-12">
                        <div class="form-group mb-0">
                            <div class="adm-photos">
                                <label class="adm-dropzone" for="images">
                                    <span class="adm-dropzone-ico"><i class="fas fa-cloud-upload-alt" aria-hidden="true"></i></span>
                                    <span class="adm-dropzone-text"><strong>Add photos</strong><small data-upload-status>JPG, PNG or WebP · you can pick several at once</small></span>
                                    <span class="btn btn-secondary btn-sm">Choose files</span>
                                </label>
                                <input type="file" class="adm-file-input" id="images"
                                    x-data x-on:change="window.uploadResized($event, $wire)" accept="image/*" multiple><script src="{{ asset("js/resize-upload.js") }}?v={{ filemtime(public_path("js/resize-upload.js")) }}"></script>
                                @error('uploadedImages.*')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror

                                @if (isset($images) && count($images))
                                    <div class="adm-photos-head">
                                        <span class="adm-photos-title">Photos ({{ count($images) }})</span>
                                        <span class="adm-photos-hint">Drag to reorder · the first photo is the main one</span>
                                    </div>
                                    <div class="adm-photo-grid" wire:sortable="updateImagesOrder"
                                        wire:sortable.options="{ animation: 300 ,removeCloneOnHide: true}">
                                        @foreach ($images as $imageItem)
                                            <div wire:sortable.item="{{ $imageItem['id'] }}" wire:key="task-{{ $imageItem['id'] }}"
                                                wire:sortable.handle @class(['adm-photo', 'is-main' => $loop->first])>
                                                <img src="{{ thumb($imageItem['image']) }}" alt="" loading="lazy">
                                                @if ($loop->first)
                                                    <span class="adm-photo-badge">Main</span>
                                                @endif
                                                <div class="adm-photo-actions">
                                                    <a href="{{ asset($imageItem['image']) }}" target="_blank" rel="noopener"
                                                        class="adm-photo-btn" title="View full size" aria-label="View full size">
                                                        <i class="fas fa-expand" aria-hidden="true"></i>
                                                    </a>
                                                    @if (!$loop->first)
                                                        <button type="button" class="adm-photo-btn is-danger" title="Delete photo" aria-label="Delete photo"
                                                            wire:loading.attr="disabled" wire:confirm="Delete this photo?"
                                                            wire:click="deleteImage('{{ $imageItem['id'] }}')">
                                                            <i class="fas fa-trash" aria-hidden="true"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if (isset($imagesWithOrders) && count($imagesWithOrders))
                                    @php
                                        $imagesWithOrders = collect($imagesWithOrders)->sortBy('order')->values()->all();
                                    @endphp
                                    <div class="adm-photos-head">
                                        <span class="adm-photos-title">New photos ({{ count($imagesWithOrders) }})</span>
                                        <span class="adm-photos-hint">Not saved yet: click Save to add them</span>
                                    </div>
                                    <div class="adm-photo-grid" wire:sortable="updateNewImagesOrder"
                                        wire:sortable.options="{ animation: 300 ,removeCloneOnHide: true}">
                                        @foreach ($imagesWithOrders as $index => $imageItem)
                                            <div wire:sortable.item="{{ $imageItem['order'] }}" wire:key="task-{{ $imageItem['order'] }}"
                                                wire:sortable.handle class="adm-photo is-new">
                                                <img src="{{ $imageItem['file']->temporaryUrl() }}" alt="">
                                                <span class="adm-photo-badge is-new">New</span>
                                                <div class="adm-photo-actions">
                                                    <button type="button" class="adm-photo-btn is-danger" title="Remove" aria-label="Remove new photo"
                                                        wire:click.prevent="removeImage({{ $index }})">
                                                        <i class="fas fa-times" aria-hidden="true"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                            <div x-show="isUploading">
                                <span max="100" x-text="`${progress}%`"></span>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" :style="`width: ${progress}%`"
                                        aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="adm-savebar">
                <span class="adm-savebar-text">Save to apply your changes</span>
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:loading.remove
                    wire:target="save, uploadedImages" x-bind:disabled="isUploading"
                    x-bind:class="{ 'btn-secondary': isUploading }" x-text="isUploading ? 'Uploading...' : 'Save'">
                    {{-- loading spinner --}}
                    <span wire:loading wire:target="save, uploadedImages">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </span>

                    <span wire:loading.remove>
                        Save
                    </span>
                </button>
                {{-- spinner btn --}}
                <button type="button" class="btn btn-secondary ms-2" wire:target="save, uploadedImages"
                    wire:loading.attr="disabled" wire:loading>
                    <span>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </span>
                </button>

                <p x-show="isError" class="alert alert-danger ms-2" x-text="errMessage">
                    an error occured while uploading, please try again
                </p>
            </div>
        </div>
    </div>



    {{-- Print all errors if exists --}}
    @if ($errors->any())
        <div class="mt-3 alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</form>


@script
    <script>
        $(document).ready(function() {
            // ------ Description ar ------
            let desc = document.querySelector('#description');
            // console.log('loaded');
            if (desc) {
                let textEditor = ClassicEditor
                    .create(desc, {})
                    .then((editor) => {
                        editor.model.document.on('change:data', () => {
                            const data = editor.getData();
                            @this.set('description', data);
                        });
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        });
    </script>
@endscript
