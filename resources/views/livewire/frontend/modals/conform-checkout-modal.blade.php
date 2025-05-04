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
        {{-- Shipping Address --}}
        <div class="mb-3">
            <label for="shipping_address" class="form-label">Shipping Address</label>
            <textarea class="form-control" id="shipping_address" rows="3" wire:model="shipping_address"></textarea>
            @error('shipping_address')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        @auth('dealer')
            @if (auth('dealer')->user()->is_customer_mode_active)
                @php
                    $dealerPercentage = auth('dealer')->user()->fake_sale_percentage;
                    $total = (float) $total * (float) $dealerPercentage;
                @endphp
            @endif
        @endauth

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
