<form wire:submit.prevent="save">
    <div class="card-body">
        <div class="row">
            {{-- Name --}}
            <div class="col-12">
                <div class="form-group">
                    <label for="e-name">Name
                        <span class="text-danger">
                            *
                        </span>
                    </label>
                    <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name')]) id="e-name" name="name" required
                        value="{{ old('name') }}" wire:model="name">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- Email --}}
            <div class="col-12">
                <div class="form-group">
                    <label for="e-email">Email
                        <span class="text-danger">
                            *
                        </span>
                    </label>
                    <input type="email" @class(['form-control', 'is-invalid' => $errors->has('email')]) id="e-email" name="email" required
                        value="{{ old('email') }}" wire:model="email">

                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
        <!-- /.card-body -->
        <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
            Update
        </button>
    </div>
</form>
