<form wire:submit.prevent="save">
    <div class="row">
        {{-- SKU --}}
        <div class="col-12">
            <div class="form-group">
                <label for="sku">SKU
                    <span class="text-danger">
                        *
                    </span>
                </label>
                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('sku')]) id="sku" name="sku" required wire:model="sku"
                    value="{{ old('sku') }}">
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
                <label for="barcode">Barcode
                </label>
                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('barcode')]) id="barcode" name="barcode" wire:model="barcode"
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
                <label for="price">Price
                    <span class="text-danger">
                        *
                    </span>
                </label>
                <input type="number" @class(['form-control', 'is-invalid' => $errors->has('price')]) id="price" name="price" required
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
                <label for="compare_at_price">Compare-at Price
                    <span class="text-danger">
                        *
                    </span>
                </label>
                <input type="number" @class([
                    'form-control',
                    'is-invalid' => $errors->has('compare_at_price'),
                ]) id="compare_at_price" name="compare_at_price"
                    required wire:model="compare_at_price" value="{{ old('compare_at_price') }}">
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
                <label for="cost_price">Cost Price
                    <span class="text-danger">
                        *
                    </span>
                </label>
                <input type="number" @class(['form-control', 'is-invalid' => $errors->has('cost_price')]) id="cost_price" name="cost_price" required
                    wire:model="cost_price" value="{{ old('cost_price') }}">
                @error('cost_price')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        {{-- Image --}}
        {{-- Image --}}
        <div class="col-12">
            <div class="form-group">
                <label for="image">Image
                    <span class="text-danger">
                        *
                    </span>
                </label>
                <input type="file" @class(['form-control', 'is-invalid' => $errors->has('image')]) id="image" name="image" required
                    value="{{ old('image') }}" wire:model="image" accept="image/*">
                @error('image')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

                @if ($image)
                    <img src="{{ $image->temporaryUrl() }}" alt="Image" class="mt-2 img-fluid img-thumbnail"
                        style="max-height: 200px;">
                @endif

            </div>
        </div>



        {{-- Description --}}
        <div class="col-12" wire:ignore>
            <div class="form-group">
                <label for="description">Description
                    <span class="text-danger">
                        *
                    </span>
                </label>
                <textarea @class(['form-control', 'is-invalid' => $errors->has('description')]) id="description" name="description" required wire:model="description">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <hr>
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
