{{-- SEO for one storefront page: title and description with the lengths search engines show --}}
@php
    // Other pages get " | Company" added after the title, so their own title has less room
    $suffix = ' | ' . (getWebsiteTitle() ?: config('app.name'));
    $isHome = $name === 'home';
    $titleMax = $isHome ? 60 : 60 - mb_strlen($suffix);
@endphp
<form wire:submit.prevent="update" x-data="{ t: @entangle('title'), d: @entangle('meta_desc') }">
    <div class="card-body">
        <div class="form-group">
            <label class="form-label">Page</label>
            <p class="adm-static">{{ $name ? ucwords(str_replace(['_', '-'], ' ', $name)) : '—' }}</p>
        </div>

        <div class="form-group">
            <label for="seo_title" class="form-label">Title</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="seo_title" x-model="t">
            <div class="adm-counter" x-bind:class="{ 'is-over': (t || '').length > {{ $titleMax }} }">
                <span>
                    @if ($isHome)
                        The headline in Google · include the company name · up to 60 characters
                    @else
                        The headline in Google · “{{ $suffix }}” is added after it · up to {{ $titleMax }} characters
                    @endif
                </span>
                <span x-text="(t || '').length + ' / {{ $titleMax }}'"></span>
            </div>
            @error('title')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="meta_desc" class="form-label">Meta description</label>
            <textarea class="form-control @error('meta_desc') is-invalid @enderror" id="meta_desc" x-model="d" rows="4"></textarea>
            <div class="adm-counter" x-bind:class="{ 'is-over': (d || '').length > 160 }">
                <span>The short text under the headline · aim for 120–160 characters</span>
                <span x-text="(d || '').length + ' / 160'"></span>
            </div>
            @error('meta_desc')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="keywords" class="form-label">Keywords</label>
            <textarea class="form-control @error('keywords') is-invalid @enderror" id="keywords" wire:model="keywords" rows="2"></textarea>
            <small class="adm-help">Separate with commas.</small>
            @error('keywords')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="adm-form-actions">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancel</button>
            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="update">
                <span wire:loading wire:target="update" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                Save
            </button>
        </div>
    </div>
</form>
