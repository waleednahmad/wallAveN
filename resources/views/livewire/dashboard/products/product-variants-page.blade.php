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
                            <td>{{ $product->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>SKU</th>
                            <td>{{ $product->sku ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Barcode</th>
                            <td>{{ $product->barcode ?? '-' }}</td>
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

                        <tr>
                            <th>
                                Actions
                            </th>
                            <td>
                                <a class="btn btn-sm btn-info" title="Preview"
                                    href="{{ route('frontend.product', $product->slug) }}" target="_blank">
                                    <i class="fas fa-eye "></i>
                                </a>
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
                            <th>#</th>
                            <th>Image</th>
                            <th>SKU</th>
                            <th>Attributes</th>
                            {{-- <th>Barcode</th> --}}
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($product->variants as $variant)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if ($variant->image && file_exists($variant->image))
                                        <img src="{{ asset($variant->image) }}" alt="Variant Image"
                                            class="img-thumbnail"
                                            style="width: 90px; height: 90px;background-color: #f8f9fa;">
                                    @else
                                        <img src="{{ asset('dashboard/images/default.webp') }}" alt="Variant Image"
                                            class="img-thumbnail"
                                            style="width: 90px; height: 90px;background-color: #f8f9fa;">
                                    @endif
                                </td>
                                <td>{{ $variant->sku }}</td>
                                <td>
                                    <ul class="list-unstyled">
                                        @php
                                            $groupedAttributes = [];
                                            foreach ($variant->attributeValues as $attributeValue) {
                                                $attributeName = $attributeValue->attribute->name;
                                                $attributeValueValue = $attributeValue->value;

                                                if (!isset($groupedAttributes[$attributeName])) {
                                                    $groupedAttributes[$attributeName] = [];
                                                }

                                                if (
                                                    !in_array($attributeValueValue, $groupedAttributes[$attributeName])
                                                ) {
                                                    $groupedAttributes[$attributeName][] = $attributeValueValue;
                                                }
                                            }
                                        @endphp
                                        @forelse ($groupedAttributes as $attributeName => $attributeValues)
                                            <li>
                                                <strong>{{ $attributeName }}:</strong>
                                                {{ implode(', ', $attributeValues) }}
                                            </li>
                                        @empty
                                            <li>No attributes found</li>
                                        @endforelse
                                    </ul>
                                </td>
                                {{-- <td>{{ $variant->barcode ?? '-' }}</td> --}}
                                <td>${{ $variant->price ?? '-' }}</td>
                                <td>
                                    {{-- Edit --}}
                                    <button class="btn btn-sm btn-primary"
                                        wire:click="editVariant({{ $variant->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    {{-- Attributes --}}
                                    {{-- <button class="btn btn-sm btn-secondary" title="Edit Attributes"
                                        wire:click="editVariantAttributes({{ $variant->id }})">
                                        <i class="fas fa-cogs"></i>
                                    </button> --}}

                                    {{-- Delete --}}
                                    <button class="btn btn-sm btn-danger" title="Delete"
                                        wire:click="deleteVariant({{ $variant->id }})">
                                        <i class="fas fa-trash"></i>
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
