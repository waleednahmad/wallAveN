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

                        {{-- Image --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">Image

                                </label>
                                <input type="file" @class(['form-control', 'is-invalid' => $errors->has('image')]) id="image" name="image"
                                    value="{{ old('image') }}" wire:model="image" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                @if ($image)
                                    <img src="{{ $image->temporaryUrl() }}" alt="Image"
                                        class="mt-2 img-fluid img-thumbnail" style="max-height: 200px;">
                                @elseif(file_exists($product->image))
                                    <img src="{{ asset($product->image) }}" alt="Image"
                                        class="mt-2 img-fluid img-thumbnail" style="max-height: 200px;">
                                @endif


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
