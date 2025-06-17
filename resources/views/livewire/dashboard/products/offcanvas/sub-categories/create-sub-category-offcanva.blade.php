<form wire:submit.prevent="save">
    <div class="card-body">
        <div class="row">
            {{-- Name --}}
            <div class="col-12">
                <div class="form-group">
                    <label for="c_sub_name">Name
                        <span class="text-danger">
                            *
                        </span>
                    </label>
                    <input type="text" @class(['form-control', 'is-invalid' => $errors->has('c_sub_name')]) id="c_sub_name" name="c_sub_name" required
                        value="{{ old('c_sub_name') }}" wire:model="c_sub_name">
                    @error('c_sub_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- Image --}}
            <div class="col-12">
                <div class="form-group">
                    <label for="c_sub_image">Image
                    </label>
                    <input type="file" @class(['form-control', 'is-invalid' => $errors->has('c_sub_image')]) id="c_sub_image" name="c_sub_image"
                        accept="image/*" wire:model="c_sub_image">
                    @error('c_sub_image')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    @if ($c_sub_image)
                        <div class="mt-2">
                            <img src="{{ $c_sub_image->temporaryUrl() }}" alt="Image Preview" class="img-thumbnail"
                                style="max-width: 200px;">
                        </div>
                    @endif
                </div>
            </div>


            {{-- Status --}}
            <div class="col-12">
                <div class="form-group">
                    <label for="c_sub_status">Status
                        <span class="text-danger">
                            *
                        </span>
                    </label>
                    <select @class(['form-control', 'is-invalid' => $errors->has('c_sub_status')]) id="c_sub_status" name="c_sub_status" required
                        wire:model="c_sub_status">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    @error('c_sub_status')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- Category ID --}}
            <div class="col-12">
                <div class="form-group">
                    <label for="c_main_category_id">Category
                        <span class="text-danger">
                            *
                        </span>
                    </label>
                    <select @class([
                        'form-control',
                        'is-invalid' => $errors->has('c_main_category_id'),
                    ]) id="c_main_category_id" name="c_main_category_id" required
                        wire:model="c_main_category_id">
                        <option value="">Select Category</option>
                        @foreach ($this->mainCategories as $main_category)
                            <option value="{{ $main_category->id }}">{{ $main_category->name }}</option>
                        @endforeach
                    </select>
                    @error('c_main_category_id')
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
