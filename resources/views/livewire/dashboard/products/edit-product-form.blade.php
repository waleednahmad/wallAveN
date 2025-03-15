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
                                <div class="alert alert-danger">
                                    No Sub Categories Found
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
                                <div class="alert alert-danger">
                                    No Product Types Found
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

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6>
                        Product Images
                    </h6>
                </div>
                <div class="card-body">
                    {{-- Image --}}
                    <div class="col-12">
                        <div class="form-group">
                            <div class="form-group">
                                <label for="images">Upload images</label>
                                <input type="file" class="form-control" id="images" wire:model="uploadedImages"
                                    accept="image/*" multiple>
                                @error('uploadedImages.*')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Preview the images -->

                            @if (isset($images) && count($images))
                                <h3>images</h3>
                                <div class="image-preview-container">
                                    <div class="images-container" wire:sortable="updateImagesOrder"
                                        wire:sortable.options="{ animation: 300 ,removeCloneOnHide: true}">
                                        @foreach ($images as $imageItem)
                                            <div wire:sortable.item="{{ $imageItem['id'] }}"
                                                @class(['single-file-card', 'active' => $loop->first]) wire:key="task-{{ $imageItem['id'] }}"
                                                wire:sortable.handle>
                                                <div class="card-body d-flex flex-column align-items-center">
                                                    {{-- image preview --}}
                                                    <img src="{{ asset($imageItem['image']) }}" class="img-fluid"
                                                        alt="file"
                                                        style="height: 100px; width: 100%;object-fit: contain;">

                                                    <div class="mt-1 d-flex" style="gap: 5px">
                                                        {{-- preview icon span --}}
                                                        <a href="{{ asset($imageItem['image']) }}" target="_blank"
                                                            class="btn btn-primary btn-sm preview-btn">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        {{-- delete icon span --}}
                                                        <button class="btn btn-danger btn-sm delete-btn"
                                                            wire:loading.attr="disabled" type="button"
                                                            wire:click="removeImage('{{ $imageItem['id'] }}')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if (isset($imagesWithOrders) && count($imagesWithOrders))
                            <hr>
                            <h4>new images</h4>

                                @php
                                    $imagesWithOrders = collect($imagesWithOrders)->sortBy('order')->values()->all();
                                @endphp

                                <div class="image-preview-container">
                                    <div class="images-container" wire:sortable="updateNewImagesOrder"
                                        wire:sortable.options="{ animation: 300 ,removeCloneOnHide: true}">
                                        @foreach ($imagesWithOrders as $index => $imageItem)
                                            <!-- Skip the current main image -->
                                            <div wire:sortable.item="{{ $imageItem['order'] }}"
                                                @class(['single-file-card'])
                                                wire:key="task-{{ $imageItem['order'] }}" wire:sortable.handle>

                                                <img src="{{ $imageItem['file']->temporaryUrl() }}" class="img-fluid"
                                                    alt="file"
                                                    style="height: 100%; width: 100%; object-fit: contain;">

                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- submit btn (in the center) --}}
    <div class="d-flex justify-content-center">
        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
            {{-- loading spinner --}}
            <span wire:loading>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            </span>
            <span wire:loading.remove>
                Save
            </span>
        </button>
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
