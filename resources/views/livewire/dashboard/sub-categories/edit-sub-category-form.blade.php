<form wire:submit.prevent="save">
    <div class="card-body">
        <div class="row">
            {{-- Name --}}
            <div class="col-12">
                <div class="form-group">
                    <label for="e_name">Name
                        <span class="text-danger">
                            *
                        </span>
                    </label>
                    <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name')]) id="e_name" name="name" required
                        value="{{ old('name') }}" wire:model="name">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- Status --}}
            <div class="col-12">
                <div class="form-group">
                    <label for="e_status">Status
                        <span class="text-danger">
                            *
                        </span>
                    </label>
                    <select @class(['form-control', 'is-invalid' => $errors->has('status')]) id="e_status" name="status" required wire:model="status">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- Category --}}
            <div class="col-12">
                <div class="form-group">
                    <label for="e_category_id">Category
                        <span class="text-danger">
                            *
                        </span>
                    </label>
                    <select @class(['form-control', 'is-invalid' => $errors->has('category_id')]) id="e_category_id" name="category_id" required
                        wire:model="category_id">
                        @foreach ($this->categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
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
