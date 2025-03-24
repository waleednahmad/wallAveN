<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="addNewValueModalLabel">
            Add New Value For "{{ $attribute?->name }}"
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form wire:submit='save'>
        <div class="modal-body">
            <div class="form-group">
                <label for="name">
                    Value
                </label>
                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('value')]) id="value" name="name" wire:model="value"
                    value="{{ old('value') }}">
                @error('value')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
