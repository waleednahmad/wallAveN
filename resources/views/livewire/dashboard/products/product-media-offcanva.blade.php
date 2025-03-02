<form wire:submit.prevent="save" enctype="multipart/form-data">
    <div class="offcanvas-body">
        <h3>{{ $product?->name }}</h3>
        <hr>
        <form wire:submit.prevent="save">
            <div class="form-group">
                <label for="images">Upload images</label>
                <input type="file" class="form-control" id="images" wire:model="uploadedImages" accept="image/*"
                    multiple>
                @error('uploadedImages.*')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                <span wire:loading wire:target='save' class="spinner-border spinner-border-sm" role="status"
                    aria-hidden="true"></span>
                Upload
            </button>
        </form>
        <hr>
        @if (isset($images) && count($images))
            <h3>images</h3>
            <div class="row">
                @foreach ($images as $image)
                    <div class="col-6">
                        <div class="card" style="background: rgba(208, 208, 208, 0.466)">
                            <div class="card-body single-file-card">
                                <img src="{{ asset($image['image']) }}" class="img-fluid" alt="file"
                                    style="height: 100px; width: 100%;object-fit: contain;">

                                <div class="mt-1 d-flex" style="gap: 5px">
                                    {{-- preview icon span --}}
                                    <a href="{{ asset($image['image']) }}" target="_blank"
                                        class="btn btn-primary btn-sm preview-btn">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    {{-- delete icon span --}}
                                    <button class="btn btn-danger btn-sm delete-btn" wire:loading.attr="disabled"
                                        type="button" wire:click="removeImage('{{ $image['id'] }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</form>
