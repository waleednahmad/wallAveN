<div class="single-content style-2">
    <h5>Password Info</h5>
    <form wire:submit.prevent="updatePassword">
        <div class="row">
            <div class="col-md-6">
                <div class="form-inner mb-30">
                    <label>Password</label>
                    <input id="password" type="password" placeholder="*****" wire:model="password">
                    <i class="bi bi-eye-slash" id="togglePassword"></i>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-inner mb-30">
                    <label>Confirm Password *</label>
                    <input id="password2" type="password" placeholder="*****" wire:model="password_confirmation">
                    <i class="bi bi-eye-slash" id="togglePassword2"></i>
                    @error('password_confirmation')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-inner style-2">
            <button type="submit" class="primary-btn2 btn-hover" wire:loading.attr="disabled"
                wire:target="updatePassword">
                {{-- spinner --}}
                <span wire:loading wire:target="updatePassword" class="spinner-border spinner-border-sm" role="status"
                    aria-hidden="true"></span>
                <span>Update Password</span>
                <strong></strong>
            </button>

        </div>
    </form>
</div>
