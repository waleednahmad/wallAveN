<div class="single-content style-2">
    <h5>Update Banner Info</h5>
    <form wire:submit.prevent="updateBannerSettings">
        <div class="row">

            {{-- Fake sale percentage --}}
            <div class="col-md-6">
                <div class="form-inner mb-30">
                    <label>Sale Percentage</label>
                    <input id="fake_sale_percentage" type="number" step=".01" min="1" max="100"
                        wire:model="fake_sale_percentage" class="form-control">
                    @error('fake_sale_percentage')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-inner mb-30">
                    <label>Text</label>
                    <textarea id="text" placeholder="Enter text here..." wire:model="text" class="form-control"></textarea>
                    @error('text')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-inner mb-30">
                    <label>Text Color</label>
                    <input id="text_color" type="color" wire:model="text_color" class="form-control">
                    @error('text_color')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-inner mb-30">
                    <label>Background Color</label>
                    <input id="bg_color" type="color" wire:model="bg_color" class="form-control">
                    @error('bg_color')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-inner style-2">
            <button type="submit" class="primary-btn2 btn-hover" wire:loading.attr="disabled"
                wire:target="updateBannerSettings">
                {{-- spinner --}}
                <span wire:loading wire:target="updateBannerSettings" class="spinner-border spinner-border-sm"
                    role="status" aria-hidden="true"></span>
                <span>Update Banner</span>
                <strong></strong>
            </button>
        </div>
    </form>
</div>
