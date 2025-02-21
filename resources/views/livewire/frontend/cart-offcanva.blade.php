<div>
    @forelse ($cartTemps as $item)
        <div class="p-2 mb-1 cart-item d-flex border-bottom align-items-center">
            <div class="mr-3 cart-item-image" style="width:130px !important">
                <img src="{{ $item->variant_image }}" alt="{{ $item->title }}" class="img-thumbnail"
                    style="width: 100% !important">
            </div>
            <div class="p-2 cart-item-content flex-grow-1">
                <h6 class="mb-1">{{ $item->title }}</h6>
                <p class="text-muted">
                    {{ $item->option1_value }} | {{ $item->option2_value }} | {{ $item->option3_value }}
                </p>
                <p>
                    ${{ $item->price ?? 0 }} x {{ $item->quantity }} = ${{ $item->total }}
                </p>
            </div>
            <div class="ml-3 cart-item-action">
                <button wire:click="removeFromCart('{{ $item->id }}')" class="btn btn-danger btn-sm">
                    X
                </button>
            </div>
        </div>
    @empty
        <p class="text-center">Your cart is empty.</p>
    @endforelse

    @if (count($cartTemps))
        <div class="mt-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Total:  $ {{ $total }}</h6>
            <button class="btn btn-sm" style="background-color: #000; color: #fff; padding: 5px 10px; font-size: 14px;"
                wire:click="checkout">Checkout</button>
        </div>
    @endif
</div>
