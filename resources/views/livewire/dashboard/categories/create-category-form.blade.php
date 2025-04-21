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

            {{-- Image --}}
            <div class="col-12">
                <div class="form-group">
                    <label for="image">Image
                    </label>
                    <input type="file" @class(['form-control', 'is-invalid' => $errors->has('image')]) id="image" name="image" accept="image/*"
                        wire:model="image">
                    @error('image')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    @if ($image)
                        <div class="mt-2">
                            <img src="{{ $image->temporaryUrl() }}" alt="Image Preview" class="img-thumbnail"
                                style="max-width: 200px;">
                        </div>
                    @endif
                </div>
            </div>

            {{-- Breadcrumb Image --}}
            <div class="col-12">
                <div class="form-group mb-3">
                    <label for="breadcrumb_image">Breadcrumb Image</label>
                    <input type="file" accept="image/*" id="breadcrumb_image" wire:model="breadcrumb_image" class="form-control">
                    @error('breadcrumb_image') <span class="text-danger">{{ $message }}</span> @enderror
                    @if ($breadcrumb_image)
                        <div class="mt-2">
                            <img src="{{ $breadcrumb_image->temporaryUrl() }}" alt="Breadcrumb Image" style="max-width: 200px;">
                        </div>
                    @endif
                </div>
            </div>

            {{-- Description --}}
            <div class="col-12">
                <div class="form-group mb-3">
                    <label for="description">Description</label>
                    <textarea id="description" wire:model.lazy="description" class="form-control" rows="3"></textarea>
                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Status --}}
            <div class="col-12">
                <div class="form-group">
                    <label for="status">Status
                        <span class="text-danger">
                            *
                        </span>
                    </label>
                    <select @class(['form-control', 'is-invalid' => $errors->has('status')]) id="status" name="status" required wire:model="status">
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
