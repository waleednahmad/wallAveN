<form wire:submit.prevent="save">
    <div class="card-body">
        <h6>
            Representative : {{ $representative?->name }}
        </h6>
        <hr>
        <div class="row">
            {{-- Password --}}
            <div class="col-12">
                <div class="form-group">
                    <label for="u-password">
                        Password
                        <span class="text-danger">
                            *
                        </span>
                    </label>
                    <input type="password" @class(['form-control', 'is-invalid' => $errors->has('password')]) id="u-password" name="password" required
                        wire:model="password">
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- Confirm Password --}}
            <div class="col-12">
                <div class="form-group">
                    <label for="u-password_confirmation">Confirm Password
                        <span class="text-danger">
                            *
                        </span>
                    </label>
                    <input type="password" @class([
                        'form-control',
                        'is-invalid' => $errors->has('password_confirmation'),
                    ]) id="u-password_confirmation"
                        name="password_confirmation" required wire:model="password_confirmation">
                    @error('password_confirmation')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="adm-form-actions">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancel</button>
            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
            Update password
        </button>
        </div>
    </div>
</form>
