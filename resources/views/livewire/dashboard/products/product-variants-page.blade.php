<div class="row">
    {{-- -------------------- Main Product Info -------------------- --}}
    <div class="col-12">
        <div class="card">
            {{-- Name --}}
            <div class="card-header">
                <h6>
                    Product Info
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <table class="table table-bordered">
                        <tr>
                            <th>Name</th>
                            <td>{{ $product->name }}</td>
                        </tr>
                        <tr>
                            <th>SKU</th>
                            <td>{{ $product->sku }}</td>
                        </tr>
                        <tr>
                            <th>Barcode</th>
                            <td>{{ $product->barcode }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if ($product->status == 1)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        {{-- Attributes --}}
                        <tr>
                            <th>Attributes</th>
                            <td class="flex-wrap d-flex" style="gap: 5px;">
                                @forelse ($product->attributes as $attribute)
                                    <span class="badge badge-secondary">{{ $attribute->name }}</span>
                                @empty
                                    <span class="badge badge-danger">No attributes found</span>
                                @endforelse
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            {{-- Name --}}
            <div class="card-header">
                <div class=" d-flex justify-content-between align-items-center">
                    <h6>
                        Product Variants
                    </h6>
                    <button class="float-right btn btn-primary" wire:click="addVariant">
                        Add new variant
                    </button>

                </div>
            </div>

            <div class="card-body">
                <table class="table table-bordered">
                    <thead>

                        <tr>
                            <th>SKU</th>
                            <th>Barcode</th>
                            <th>Compare-at Price</th>
                            <th>Cost Price</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($product->variants as $variant)
                            <tr>
                                <td>{{ $variant->sku }}</td>
                                <td>{{ $variant->barcode }}</td>
                                <td>{{ $variant->compare_at_price ?? '-' }}</td>
                                <td>{{ $variant->cost_price ?? '-' }}</td>
                                <td>{{ $variant->price ?? '-' }}</td>
                                <td>
                                    {{-- Edit --}}
                                    <button class="btn btn-sm btn-primary"
                                        wire:click="editVariant({{ $variant->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    {{-- Attributes --}}
                                    <button class="btn btn-sm btn-secondary" title="Edit Attributes"
                                        wire:click="editVariantAttributes({{ $variant->id }})">
                                        <i class="fas fa-cogs"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No variants found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
