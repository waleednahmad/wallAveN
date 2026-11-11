@php
    $grTotal = (float) $total;
    if (auth('dealer')->check() && auth('dealer')->user()->is_customer_mode_active) {
        $grTotal = $grTotal * (float) auth('dealer')->user()->fake_sale_percentage;
    }
    $grTotalLabel = '$' . number_format($grTotal, 2);
@endphp
<div>
    <div class="modal-body" style="padding: 8px 28px 0;">
        <h2 style="font-family: var(--gr-font-display); font-size: 30px; margin: 0 0 4px;">Checkout</h2>
        <p style="color: var(--gr-muted); margin: 0 0 24px;">Check the details below, then place your order.</p>

        <div class="mb-3">
            <label for="po_number" class="form-label">Purchase order number <span style="font-weight: 400; color: var(--gr-muted);">(optional)</span></label>
            <input type="text" class="form-control" id="po_number" wire:model="po_number" placeholder="e.g. PO-2026-118">
            <p class="gr-help">Shown on your invoice so you can match it to your records.</p>
            @error('po_number')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="shipping_address" class="form-label">Shipping address</label>
            <textarea class="form-control" id="shipping_address" rows="3" wire:model="shipping_address"></textarea>
            @error('shipping_address')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-baseline" style="border-top: 1px solid var(--gr-line); padding: 18px 0 4px;">
            <span style="font-weight: 600;">Order total</span>
            <span style="font-family: var(--gr-font-display); font-size: 30px;">{{ $grTotalLabel }}</span>
        </div>
    </div>
    <div class="modal-footer" style="border-top: 0; padding: 12px 28px 28px; display: flex; flex-direction: column; gap: 10px; align-items: stretch;">
        <button type="button" class="gr-btn gr-btn-dark w-100" wire:click="checkout" wire:loading.attr="disabled">
            <span wire:loading wire:target="checkout" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Place order · {{ $grTotalLabel }}
        </button>
        <button type="button" class="gr-btn gr-btn-outline w-100" data-bs-dismiss="modal">Back to cart</button>
        <p style="margin: 0; font-size: 12px; color: var(--gr-muted); text-align: center;">You'll get a confirmation email. Our team will contact you to arrange delivery.</p>
    </div>
</div>
