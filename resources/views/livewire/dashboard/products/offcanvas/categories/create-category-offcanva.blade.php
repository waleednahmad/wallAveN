<form wire:submit.prevent="save">
    <div class="card-body">
        <div class="row">
            {{-- Name --}}
            <div class="col-12">
                <div class="form-group">
                    <label for="c_name">Name
                        <span class="text-danger">
                            *
                        </span>
                    </label>
                    <input type="text" @class(['form-control', 'is-invalid' => $errors->has('c_name')]) id="c_name" name="c_name" required
                        wire:model="c_name">
                    @error('c_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- Image --}}
            <div class="col-12">
                <div class="form-group">
                    <label for="c_image">Image
                    </label>
                    <input type="file" @class(['form-control', 'is-invalid' => $errors->has('c_image')]) id="c_image" name="c_image" accept="image/*"
                        wire:model="c_image">
                    @error('c_image')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    @if ($c_image)
                        <div class="mt-2">
                            <img src="{{ $c_image->temporaryUrl() }}" alt="Image Preview" class="img-thumbnail"
                                style="max-width: 200px;">
                        </div>
                    @endif
                </div>
            </div>

            {{-- Breadcrumb Image --}}
            <div class="col-12">
                <div class="form-group mb-3">
                    <label for="c_breadcrumb_image">Breadcrumb Image</label>
                    <input type="file" accept="image/*" id="c_breadcrumb_image" wire:model="c_breadcrumb_image"
                        class="form-control">
                    @error('c_breadcrumb_image')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    @if ($c_breadcrumb_image)
                        <div class="mt-2">
                            <img src="{{ $c_breadcrumb_image->temporaryUrl() }}" alt="Breadcrumb Image"
                                style="max-width: 200px;">
                        </div>
                    @endif
                </div>
            </div>

            {{-- Description --}}
            <div class="col-12">
                <div class="form-group mb-3">
                    <label for="c_description">Description</label>
                    <textarea id="c_description" wire:model.lazy="c_description" class="form-control" rows="3"></textarea>
                    @error('c_description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Status --}}
            {{-- <div class="col-12">
                <div class="form-group">
                    <label for="c_status">Status
                        <span class="text-danger">
                            *
                        </span>
                    </label>
                    <select @class(['form-control', 'is-invalid' => $errors->has('c_status')]) id="c_status" name="c_status" required wire:model="c_status">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    @error('c_status')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div> --}}
        </div>

        {{-- Errors $errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
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
