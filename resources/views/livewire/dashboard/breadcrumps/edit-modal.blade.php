<form wire:submit.prevent="save">
    <div class="modal-body">
        <div class="row">
            {{-- title --}}
            <div class="col-12">
                <div class="form-group">
                    <label for="title">Title
                        <span class="text-danger">
                            *
                        </span>
                    </label>
                    <input type="text" @class(['form-control', 'is-invalid' => $errors->has('title')]) id="title" required wire:model="title">
                    @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- Description --}}
            <div class="col-12">
                <div class="form-group">
                    <label for="description">Description
                        <span class="text-danger">
                            *
                        </span>
                    </label>
                    <textarea class="form-control" id="description" rows="5" required wire:model="description"></textarea>
                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>



            <hr>
            {{-- image --}}

            {{-- Image --}}
            <div class="col-12">
                <div class="form-group">
                    <div class="form-group">
                        <label for="images">Breadcrump Image</label>
                        <input type="file" class="form-control customized" id="images" wire:model="uploadImage"
                            accept="image/*">
                        @error('uploadImage')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Errors --}}
            @if ($errors->any())
                <div class="col-12">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save, image">
                    {{-- loading spinner --}}
                    <span wire:loading>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </span>

                    <span wire:loading.remove>
                        Save
                    </span>
                </button>

            </div>

        </div>
    </div>
</form>
