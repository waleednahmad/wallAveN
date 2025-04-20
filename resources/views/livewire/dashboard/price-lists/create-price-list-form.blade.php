<form wire:submit.prevent="save">
    <div class="card-body">
        <div class="row">
            {{-- Name --}}
            <div class="col-12">
                <div class="form-group">
                    <label for="name">Name
                        <span class="text-danger">
                            *
                        </span>
                    </label>
                    <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name')]) id="name" name="name" required
                        value="{{ old('name') }}" wire:model="name">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- Percentage --}}
            <div class="col-12">
                <div class="form-group">
                    <label for="percentage">Discount Percentage
                        <span class="text-danger">
                            *
                        </span>
                    </label>
                    <input type="number" @class(['form-control', 'is-invalid' => $errors->has('percentage')]) id="percentage" name="percentage" required
                        min="1" max="100" wire:model="percentage">
                    @error('percentage')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
        <hr>
        <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
            {{-- loading spinner --}}
            <span wire:loading>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            </span>
            <span wire:loading.remove>
                Save
            </span>
        </button>
    </div>
</form>
