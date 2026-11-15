{{-- Banner at the top of a storefront page: heading, short text, wide image --}}
<form wire:submit.prevent="save">
    <div class="modal-body">
        <div class="form-group">
            <label for="title">Heading <span class="text-danger">*</span></label>
            <input type="text" @class(['form-control', 'is-invalid' => $errors->has('title')]) id="title" required wire:model="title">
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="description">Text under the heading <span class="text-danger">*</span></label>
            <textarea @class(['form-control', 'is-invalid' => $errors->has('description')]) id="description" rows="3" required wire:model="description"></textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group mb-0">
            <label for="banner_image">Banner image</label>
            @if ($uploadImage)
                <img src="{{ $uploadImage->temporaryUrl() }}" alt="" class="adm-banner-preview">
            @elseif ($image && file_exists(public_path($image)))
                <img src="{{ asset($image) }}" alt="" class="adm-banner-preview">
            @endif
            <input type="file" class="form-control customized" id="banner_image" wire:model="uploadImage" accept="image/*">
            <small class="adm-help">A wide photo works best (about 1920 × 400). Leave empty to keep the current one. Max 4 MB.</small>
            <div wire:loading wire:target="uploadImage" class="adm-help"><span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Uploading…</div>
            @error('uploadImage')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save, uploadImage">
            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
            Save
        </button>
    </div>
</form>
