<form wire:submit.prevent="save">
    <div class="row">

        {{-- Image --}}
        <div class="col-12">
            <div class="form-group">
                <label for="e_image">Image
                </label>
                <input type="file" @class(['form-control', 'is-invalid' => $errors->has('image')]) id="e_image" name="image" value="{{ old('image') }}"
                    wire:model="image" accept="image/*">
                @error('image')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

                @if (isset($productImages) && count($productImages))
                    <div class="images-container variant">
                        @foreach ($productImages as $prImage)
                            <!-- Skip the current main image -->
                            <div @class([
                                'single-file-card',
                                'active' => $prImage['id'] == $selectedImageId,
                            ]) wire:click="setMainImage({{ $prImage['id'] }})">
                                <img src="{{ asset($prImage['image']) }}" class="img-fluid" alt="file"
                                    style="height: 100%; width: 100%; object-fit: contain;">

                                @if ($prImage['id'] == $selectedImageId)
                                    {{-- Checked icon --}}
                                    <div class="checked-icon">
                                        <i class="fas fa-check"></i>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- @if ($image)
                    <img src="{{ $image->temporaryUrl() }}" alt="Image" class="mt-2 img-fluid img-thumbnail"
                        style="max-height: 200px;">
                @endif --}}

            </div>
        </div>
        {{-- productAttributesWithValues --}}
        <div class="col-12">
            <h5 class="d-flex align-items-center justify-content-between">
                <span>
                    Attributes
                </span>
            </h5>
            <div class="category-container d-flex flex-column">
                @forelse ($this->productAttributesWithValues as $attribute)
                    <h6 class="mt-1 mb-0">
                        {{ $attribute['name'] }}

                        {{-- add new value button with modal --}}
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addNewValueModal" wire:click="setEditAttribute({{ $attribute['id'] }})">
                            <i class="fas fa-plus"></i>
                        </button>
                    </h6>
                    {{-- values --}}
                    <div class="flex-wrap gap-2 d-flex">
                        @foreach ($attribute['values'] as $value)
                            <div wire:click="selectAttributeValue({{ $attribute['id'] }},{{ $value['id'] }})"
                                @class([
                                    'category-card ',
                                    'active' => in_array($value['id'], $this->selectedAttributeValues),
                                ])>
                                <p class="mb-0">
                                    {{ $value['value'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @empty
                @endforelse
            </div>
        </div>

        {{-- SKU --}}
        <div class="mt-4 col-12">
            <div class="form-group">
                <label for="e_sku">SKU
                    <span class="text-danger">
                        *
                    </span>
                </label>
                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('sku')]) id="e_sku" name="sku" required
                    wire:model="sku" value="{{ old('sku') }}">
                @error('sku')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        {{-- Barcode --}}
        <div class="col-12">
            <div class="form-group">
                <label for="e_barcode">Barcode
                </label>
                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('barcode')]) id="e_barcode" name="barcode" wire:model="barcode"
                    value="{{ old('barcode') }}">
                @error('barcode')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        {{-- Price --}}
        <div class="col-12">
            <div class="form-group">
                <label for="e_price">Price
                    <span class="text-danger">
                        *
                    </span>
                </label>
                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('price')]) id="e_price" name="price" required
                    wire:model="price" value="{{ old('price') }}">
                @error('price')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        {{-- Full Price --}}
        <div class="col-12">
            <div class="form-group">
                <label for="e_compare_at_price">Compare-at Price

                </label>
                <input type="text" @class([
                    'form-control',
                    'is-invalid' => $errors->has('compare_at_price'),
                ]) id="e_compare_at_price" name="compare_at_price"
                    wire:model="compare_at_price" value="{{ old('compare_at_price') }}">
                @error('compare_at_price')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        {{-- Cost Price --}}
        <div class="col-12">
            <div class="form-group">
                <label for="e_cost_price">
                    Cost Price
                </label>
                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('cost_price')]) id="e_cost_price" name="cost_price"
                    wire:model="cost_price" value="{{ old('cost_price') }}">
                @error('cost_price')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>




        {{-- Description --}}
        <div class="col-12" wire:ignore>
            <div class="form-group">
                <label for="e_description">Description

                </label>
                <textarea @class(['form-control']) id="e_description" name="description" wire:model="description"></textarea>
                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>


    </div>

    <div class="mt-3 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="save">
                Save
            </span>
            <span wire:loading wire:target="save">
                Saving...
            </span>
        </button>
    </div>
</form>


@script
    <script>
        $(document).ready(function() {
            let editor;
            let desc = document.querySelector('#e_description');

            $wire.on('setVariantData', () => {
                if (editor) {
                    editor.destroy()
                        .then(() => {
                            createEditor();
                        })
                        .catch(error => {
                            console.error(error);
                        });
                } else {
                    createEditor();
                }
            });

            function createEditor() {
                ClassicEditor.create(desc, {})
                    .then((newEditor) => {
                        editor = newEditor;
                        editor.setData(@this.get('description'));
                        editor.model.document.on('change:data', () => {
                            const data = editor.getData();
                            @this.set('description', data);
                        });
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }

            // make those inputs ['price', 'compare_at_price', 'cost_price']  aaccept only positiive numbers without any symbol or text
            let priceInputs = ['e_price', 'e_compare_at_price', 'e_cost_price'];
            priceInputs.forEach(input => {
                let inputElement = document.querySelector(`#${input}`);
                if (inputElement) {
                    inputElement.addEventListener('input', function() {
                        this.value = this.value.replace(/[^0-9.]/g, '');
                    });
                }
            });

            let skuField = document.querySelector('#e_sku');
            if (skuField) {
                skuField.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            }

        });
    </script>
@endscript
