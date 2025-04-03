<div>
    @forelse ($cartTemps as $item)
        <div class="p-2 mb-1 cart-item d-flex border-bottom align-items-center">
            <div class="mr-3 cart-item-image" style="width:130px !important">
                <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="img-thumbnail"
                    style="width: 100% !important">
            </div>
            <div class="p-2 cart-item-content flex-grow-1">
                <h6 class="mb-1">{{ $item->name }}</h6>
                @if ($item->item_type == 'variant')
                    @php
                        $attributes = json_decode($item->attributes, true);
                        $values = implode(' | ', array_map('ucwords', array_values($attributes)));
                    @endphp
                    <p>
                        <small class="text-muted">({{ $values }})</small>
                    </p>
                @endif
                @php
                    $cartQuantity = $item->quantity;
                    $itemPrice = $item->price;
                    $itemTotal = $item->total;
                @endphp

                @auth('dealer')
                    @php
                        $dealerPercentage = auth('dealer')->user()->fake_sale_percentage;
                        $itemPrice = $itemPrice * $dealerPercentage;
                        $itemTotal = $itemTotal * $dealerPercentage;
                        $total = $total * $dealerPercentage;
                    @endphp
                @endauth
                <p>
                    {{ $item->quantity }} x ${{ $itemPrice ?? 0 }} = ${{ $itemTotal }}
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
            <h6 class="mb-0">Total: $ {{ $total }}</h6>
            <button class="btn btn-sm" style="background-color: #000; color: #fff; padding: 5px 10px; font-size: 14px;"
                wire:click="checkout">Checkout</button>
        </div>
    @endif
</div>
