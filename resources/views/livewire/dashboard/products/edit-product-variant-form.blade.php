<form wire:submit.prevent="save" novalidate>
    <div class="row">

        {{-- Variant photo: pick one of the product's photos (click again to clear).
             The old file input here never worked (the upload was not saved), so it was replaced by this note. --}}
        <div class="col-12">
            <div class="form-group">
                <div class="adm-photos-head mt-0">
                    <span class="adm-photos-title">Variant photo</span>
                    <span class="adm-photos-hint">Pick the photo dealers see for this option. Click it again to clear.</span>
                </div>
                    <p class="adm-hint-line"><i class="fas fa-bolt" aria-hidden="true"></i> Picking a photo here saves it to this variant right away.</p>

                @if (isset($productImages) && count($productImages))
                    <div class="adm-photo-grid adm-photo-pick">
                        @foreach ($productImages as $prImage)
                            @php $grPicked = $prImage['id'] == $selectedImageId; @endphp
                            <button type="button" wire:key="pick-{{ $prImage['id'] }}" wire:click="setMainImage({{ $prImage['id'] }})"
                                @class(['adm-photo', 'is-picked' => $grPicked]) aria-pressed="{{ $grPicked ? 'true' : 'false' }}"
                                aria-label="Use photo {{ $loop->iteration }} for this variant">
                                <img src="{{ thumb($prImage['image'], 240) }}" alt="" loading="lazy">
                                @if ($grPicked)
                                    <span class="adm-photo-check" aria-hidden="true"><i class="fas fa-check"></i></span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                @else
                    <div class="adm-hint">This product has no photos yet.</div>
                @endif

                @if ($product?->id)
                    <p class="adm-hint-line mt-2">
                        Need a different photo? Add it to the product's photos first
                        (<a href="{{ route('dashboard.products.edit', $product->id) }}" target="_blank" rel="noopener">open the product</a>), then pick it here.
                    </p>
                @endif
            </div>
        </div>

        {{-- productAttributesWithValues --}}
        <div class="col-12">
            <h5 class="d-flex align-items-center justify-content-between">
                <span>
                    Attributes
                </span>
            </h5>
            <div class="d-flex flex-column gap-3" x-data x-on:select-updated="$wire.selectAttributeValue($event.detail.target, $event.detail.value)">
                @forelse ($this->productAttributesWithValues as $attribute)
                    <div>
                        <div class="d-flex align-items-end gap-2 mb-1">
                            <div class="flex-grow-1">
                            <livewire:form.select-dropdown
                                :items="collect($attribute['values'])->map(fn($v) => ['id' => $v['id'], 'value' => $v['value']])->values()->toArray()"
                                :targetModel="(string) $attribute['id']"
                                :searchable="true"
                                :multiple="false"
                                :required="true"
                                :placeholder="'Select ' . $attribute['name']"
                                labelKey="value"
                                valueKey="id"
                                :label="ucwords(strtolower($attribute['name']))"
                                :value="$this->selectedAttributeValues[$attribute['id']] ?? null"
                                :key="'edit-attr-select-' . $attribute['id'] . '-' . count($attribute['values'])"
                            />
                            </div>
                            {{-- add new value button with modal --}}
                            <button type="button" class="btn btn-sm btn-primary mb-1" data-bs-toggle="modal"
                                data-bs-target="#addNewValueModal" wire:click="setEditAttribute({{ $attribute['id'] }})">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
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
        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save">
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
