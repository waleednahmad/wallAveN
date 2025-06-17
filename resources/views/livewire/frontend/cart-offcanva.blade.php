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
                        $filteredAttributes = array_filter($attributes, function ($value) {
                            return strtolower($value) !== 'none';
                        });
                        $values = implode(' | ', array_map('ucwords', array_values($filteredAttributes)));
                    @endphp
                    @if (isset($values) && !empty($values))
                        <p>
                            <small class="text-muted">({{ $values }})</small>
                        </p>
                    @endif
                @endif
                @php
                    $cartQuantity = $item->quantity;
                    $itemPrice = $item->price;
                    $itemTotal = $item->total;
                @endphp

                @auth('dealer')
                    @php
                        if (auth('dealer')->user()->is_customer_mode_active) {
                            $dealerPercentage = auth('dealer')->user()->fake_sale_percentage;
                            $itemPrice = (float) $itemPrice * (float) $dealerPercentage;
                            $itemTotal = (float) $itemTotal * (float) $dealerPercentage;
                            $total = (float) $total * (float) $dealerPercentage;
                        }
                    @endphp
                @endauth

                {{-- Quantity Controls (Hidden by default) --}}
                @if (in_array((string)$item->id, $showQuantityControls))
                    <div class="d-flex align-items-center mb-2">
                        <span class="me-2">Qty:</span>
                        <div class="d-flex align-items-center">
                            <button wire:click="decreaseQuantity('{{ $item->id }}')" type="button"
                                class="btn btn-outline-secondary btn-sm" style="padding: 2px 6px; font-size: 12px;"
                                wire:loading.attr="disabled">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" value="{{ $item->quantity }}" readonly
                                class="form-control mx-1 text-center" style="width: 50px; padding: 2px; font-size: 12px;"
                                min="1">
                            <button wire:click="increaseQuantity('{{ $item->id }}')" type="button"
                                class="btn btn-outline-secondary btn-sm" style="padding: 2px 6px; font-size: 12px;"
                                wire:loading.attr="disabled">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                @else
                    {{-- Simple quantity display when controls are hidden --}}
                    <div class="mb-0">
                        <span class="text-muted">Qty: {{ $item->quantity }}</span>
                    </div>
                @endif

                <p class="mb-0">
                    <small class="text-muted">${{ $itemPrice ?? 0 }} each</small>
                </p>
                <p class="mb-0 fw-bold">
                    Total: ${{ $itemTotal }}
                </p>
            </div>
            <div class="ml-3 cart-item-action d-flex flex-column align-items-center">
                {{-- Toggle Quantity Controls Button --}}
                <button wire:click="toggleQuantityControls('{{ $item->id }}')" 
                        class="btn btn-success btn-sm mb-1"
                        wire:loading.attr="disabled" 
                        title="{{ in_array((string)$item->id, $showQuantityControls) ? 'Hide quantity controls' : 'Show quantity controls' }}">
                    <i class="fas {{ in_array((string)$item->id, $showQuantityControls) ? 'fa-eye-slash' : 'fa-edit' }}"></i>
                </button>
                
                {{-- Remove Button --}}
                <button wire:click="removeFromCart('{{ $item->id }}')" class="btn btn-danger btn-sm mb-1"
                    wire:loading.attr="disabled" title="Remove item">
                    <i class="fas fa-trash"></i>
                </button>
                
                {{-- Loading Spinner --}}
                <div wire:loading wire:target="increaseQuantity, decreaseQuantity, updateQuantity, toggleQuantityControls"
                    class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
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
