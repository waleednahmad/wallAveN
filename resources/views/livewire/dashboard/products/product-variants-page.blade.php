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
                                    <table class="table table-sm table-bordered mb-0">
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
                                        @if (!empty($groupedAttributes))
                                            <thead>
                                                <tr>
                                                    @foreach ($groupedAttributes as $attributeName => $attributeValues)
                                                        <th>{{ $attributeName }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    @foreach ($groupedAttributes as $attributeValues)
                                                        <td>
                                                            @foreach ($attributeValues as $value)
                                                                {{ $value }}
                                                            @endforeach
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            </tbody>
                                        @else
                                            <tr>
                                                <td colspan="2" class="text-center">No attributes found</td>
                                            </tr>
                                        @endif
                                    </table>
                                </td>
                                {{-- <td>{{ $variant->barcode ?? '-' }}</td> --}}
                                <td>
                                    @if ($variant->compare_at_price && $variant->compare_at_price < $variant->price)
                                        <span style="text-decoration: line-through; color: #999999">
                                            ${{ $variant->price }}
                                        </span>
                                        <strong>${{ $variant->compare_at_price }}</strong>
                                    @else
                                        <strong>${{ $variant->price }}</strong>
                                    @endif
                                </td>
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
