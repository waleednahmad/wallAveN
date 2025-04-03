<div class="moda-body">
    {{-- will be a form with three fields [key,value,description] --}}
    <form wire:submit.prevent="update">
        <h6 class="mb-3">
            {{ $key }}
        </h6>
        <div class="mb-3">
            <label for="value" class="form-label">Value</label>

            @if ($type == 'select')
                <select class="form-control" id="value" wire:model="value">
                    <option value="1">active</option>
                    <option value="0">inactive</option>
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
        <div class="mb-3">
            @if ($type == 'image')
                {{-- reset main image --}}
                <button type="button" class="btn btn-danger" wire:click="resetImage">Reset Image</button>
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
