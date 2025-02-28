<form wire:submit.prevent="save">
    <div class="row">
        <h5>
            Variant SKU : {{ $this->variant ? $this->variant->sku : '-' }}
        </h5>
        <hr>

        <div class="col-12">
            <div class="category-container d-flex flex-column">
                @forelse ($this->productAttributesWithValues as $attribute)
                    <h6 class="mt-1 mb-0">
                        {{ $attribute['name'] }}
                    </h6>
                    {{-- values --}}
                    <div class="flex-wrap gap-2 d-flex">
                        @foreach ($attribute['values'] as $value)
                            <div wire:click="selectAttributeValue({{ $attribute['id'] }},{{ $value['id'] }})"
                                @class([
                                    'category-card ',
                                    'active' => in_array($value['id'], $this->selectedAttributeValues),
                                ])>
                                <p class="mb-0">
                                    {{ $value['value'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="save">
                Save
            </span>
            <span wire:loading wire:target="save">
                Saving...
            </span>
        </button>
    </div>
</form>



