<div class="moda-body">
    {{-- will be a form with three fields [key,value,description] --}}
    <form wire:submit.prevent="update">
        <h6 class="mb-3">
            {{ $key }}
        </h6>
        <div class="mb-3">
            <label for="value" class="form-label">Value</label>
            <input type="text" class="form-control" id="value" wire:model="value">
            @error('value')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" wire:model="description"></textarea>
            @error('description')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
