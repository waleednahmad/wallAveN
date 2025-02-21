<div>
    <div class="modal-body">
        <h1 class="modal-title fs-5">
            Are you sure you want to proceed with the checkout?
        </h1>

        <hr>
        {{-- Po Number --}}
        <div class="mb-3">
            <label for="po_number" class="form-label">P.O No</label>
            <input type="text" class="form-control" id="po_number" wire:model="po_number">
            @error('po_number')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mt-3 d-flex justify-content-between">
            <h6 class="mb-0">Total: $ {{ $total }}</h6>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn" style="background-color: #000; color: #fff;" wire:click="checkout"
            wire:loading.attr="disabled">
            {{-- Spinner --}}
            <span wire:loading wire:target="checkout" class="spinner-border spinner-border-sm" role="status"
                aria-hidden="true"></span>
            Proceed
        </button>
    </div>
</div>
