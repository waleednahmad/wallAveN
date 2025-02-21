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

            {{-- Email --}}
            <div class="col-12">
                <div class="form-group">
                    <label for="email">Email
                        <span class="text-danger">
                            *
                        </span>
                    </label>
                    <input type="email" @class(['form-control', 'is-invalid' => $errors->has('email')]) id="email" name="email" required
                        value="{{ old('email') }}" wire:model="email">

                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            {{-- Password --}}
            <div class="col-12">
                <div class="form-group">
                    <label for="password">
                        Password
                        <span class="text-danger">
                            *
                        </span>
                    </label>
                    <input type="password" @class(['form-control', 'is-invalid' => $errors->has('password')]) id="password" name="password" required
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
                    <label for="password_confirmation">Confirm Password
                        <span class="text-danger">
                            *
                        </span>
                    </label>
                    <input type="password" @class([
                        'form-control',
                        'is-invalid' => $errors->has('password_confirmation'),
                    ]) id="password_confirmation"
                        name="password_confirmation" required wire:model="password_confirmation">

                    @error('password_confirmation')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>


        <!-- /.card-body -->
        <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
            Add
        </button>
    </div>
</form>
