{{-- Variants of one product: short product summary, then one row per variant with a column per attribute --}}
@php
    $variants = $product->variants;
    $money = fn ($n) => '$' . number_format((float) $n, 2);
    // Attribute columns: the product's attributes first, then any other attribute a variant uses
    $attrNames = $product->attributes->pluck('name')->map(fn ($n) => ucwords(strtolower($n)))->all();
    foreach ($variants as $v) {
        foreach ($v->attributeValues as $av) {
            $n = ucwords(strtolower($av->attribute->name ?? ''));
            if ($n !== '' && !in_array($n, $attrNames, true)) {
                $attrNames[] = $n;
            }
        }
    }
    $prices = $variants->pluck('price')->filter(fn ($p) => $p !== null);
    $mainImage = $product->image && file_exists($product->image) ? thumb($product->image, 240) : asset('dashboard/images/default.webp');
@endphp
<div>
    {{-- Product summary --}}
    <section class="card adm-psum">
        <div class="card-body">
            <img src="{{ $mainImage }}" alt="" class="adm-psum-img">
            <div class="adm-psum-main">
                <h2 class="adm-psum-name">{{ $product->name ?: '—' }}</h2>
                <p class="adm-psum-sub">
                    SKU {{ $product->sku ?: '—' }}@if ($product->barcode) · Barcode {{ $product->barcode }}@endif
                </p>
                <div class="adm-psum-tags">
                    <span class="badge badge-{{ $product->status ? 'success' : 'secondary' }}">{{ $product->status ? 'Active' : 'Hidden' }}</span>
                    @forelse ($product->attributes as $attribute)
                        <span class="badge badge-light">{{ ucwords(strtolower($attribute->name)) }}</span>
                    @empty
                        <span class="badge badge-warning">No attributes picked on the product</span>
                    @endforelse
                </div>
            </div>
            <dl class="adm-psum-stats">
                <div><dt>Variants</dt><dd>{{ $variants->count() }}</dd></div>
                <div><dt>Price</dt><dd>{{ $prices->isEmpty() ? '—' : ($prices->min() == $prices->max() ? $money($prices->min()) : $money($prices->min()) . ' – ' . $money($prices->max())) }}</dd></div>
            </dl>
        </div>
    </section>

    {{-- Variants --}}
    <section class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title mb-0">Variants</h3>
            <button type="button" class="btn btn-primary" wire:click="addVariant">
                <i class="fas fa-plus" aria-hidden="true"></i> Add variant
            </button>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table mb-0 adm-vtable">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>SKU</th>
                        @foreach ($attrNames as $name)
                            <th>{{ $name }}</th>
                        @endforeach
                        <th class="text-right">Price</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($variants as $variant)
                        @php
                            $values = [];
                            foreach ($variant->attributeValues as $av) {
                                $values[ucwords(strtolower($av->attribute->name ?? ''))][] = $av->value;
                            }
                        @endphp
                        <tr wire:key="variant-{{ $variant->id }}">
                            <td>
                                <img src="{{ $variant->image && file_exists($variant->image) ? thumb($variant->image, 240) : asset('dashboard/images/default.webp') }}" alt="" class="adm-vtable-img" loading="lazy">
                            </td>
                            <td class="adm-nowrap">{{ $variant->sku ?: '—' }}</td>
                            @foreach ($attrNames as $name)
                                <td>{{ isset($values[$name]) ? implode(', ', array_unique($values[$name])) : '—' }}</td>
                            @endforeach
                            <td class="text-right adm-nowrap">
                                @if ($variant->compare_at_price && $variant->compare_at_price > $variant->price)
                                    <span class="adm-was">{{ $money($variant->compare_at_price) }}</span>
                                @endif
                                <strong>{{ $variant->price !== null ? $money($variant->price) : '—' }}</strong>
                            </td>
                            <td class="text-right adm-nowrap">
                                <button type="button" class="adm-row-btn" title="Edit variant" aria-label="Edit variant" wire:click="editVariant({{ $variant->id }})">
                                    <i class="fas fa-pen" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="adm-row-btn is-danger" title="Delete variant" aria-label="Delete variant" wire:click="deleteVariant({{ $variant->id }})">
                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 4 + count($attrNames) }}" class="text-center text-muted py-5">
                                No variants yet. Dealers can only order a product once it has at least one variant with a price.
                                <div class="mt-3">
                                    <button type="button" class="btn btn-primary" wire:click="addVariant"><i class="fas fa-plus" aria-hidden="true"></i> Add the first variant</button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
