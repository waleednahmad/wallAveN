{{-- One store setting: its value (text, number, on/off or image) and what it is for --}}
<div>
    <form wire:submit.prevent="update">
        <p class="adm-static mb-3"><strong>{{ $key ? ucfirst(str_replace(['-', '_'], ' ', $key)) : '' }}</strong></p>
        <div class="mb-3">
            <label for="value" class="form-label">Value</label>

            @if ($key === 'toggle show category & shop pages')
                {{-- Who sees the catalog: each company decides --}}
                <select class="form-control" id="value" wire:model="value">
                    <option value="0">Closed: only signed-in dealers see products</option>
                    <option value="1">Open: everyone sees products, prices after signing in</option>
                    <option value="2">Open with prices: everyone sees products and prices</option>
                </select>
                <small class="adm-help">Ordering always needs an approved dealer account. Open catalogs can be found on Google.</small>
            @elseif ($type == 'select')
                <select class="form-control" id="value" wire:model="value">
                    <option value="1">On</option>
                    <option value="0">Off</option>
                </select>
            @elseif($type == 'image')
                <input type="file" class="form-control" id="value" wire:model="image" accept="image/*">
                @if ($image)
                    <img src="{{ $image->temporaryUrl() }}" alt="Image Preview" class="mt-2 img-thumbnail"
                        width="100">
                @elseif($value && public_path($value))
                    <img src="{{ asset($value) }}" alt="Image Preview" class="mt-2 img-thumbnail" width="100">
                @endif

                @error('image')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            @elseif($type == 'string')
                <input type="text" class="form-control" id="value" wire:model="value">
            @elseif($type == 'text')
                <textarea class="form-control" id="value" wire:model="value" rows="10"></textarea>
            @else
                <input type="number" class="form-control" id="value" wire:model="value"
                    @if ($key == 'minimum-dealer-sale-percentage') min="0" step=".01" max="100" @endif>
            @endif
            @error('value')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" wire:model="description" rows="7"></textarea>
            @error('description')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        @if ($type == 'image')
            <div class="mb-3">
                {{-- reset main image --}}
                <button type="button" class="btn btn-secondary btn-sm" wire:click="resetImage" wire:confirm="Go back to the default image?">
                    <i class="fas fa-undo" aria-hidden="true"></i> Use the default image
                </button>
            </div>
        @endif
        <div class="adm-form-actions">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancel</button>
            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="update, image">
                <span wire:loading wire:target="update" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                Save
            </button>
        </div>
    </form>
</div>
