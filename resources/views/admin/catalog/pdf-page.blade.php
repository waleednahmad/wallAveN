@php
    $totalProducts = count($productArray);
    $actualRows = (int) ceil($totalProducts / $cols);
    // A4 = 297mm, top margin 20mm, bottom margin 14mm, header ~14mm, footer ~10mm → usable ≈ 239mm
    $usableHeight = 239;
    $rowHeight = floor($usableHeight / $rows) . 'mm';
    $imgHeight = $layout === '3x3' ? '45mm' : '65mm';
    $cardPadding = $layout === '3x3' ? '2mm' : '3mm';
    $nameFontSize = $layout === '3x3' ? '10pt' : '12pt';
    $detailFontSize = $layout === '3x3' ? '8pt' : '10pt';
    $priceFontSize = $layout === '3x3' ? '11pt' : '13pt';
    $skuFontSize = $layout === '3x3' ? '7pt' : '9pt';
    $colWidth = $layout === '3x3' ? '33%' : '50%';
@endphp
<table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
    @for ($row = 0; $row < $actualRows; $row++)
        <tr>
            @for ($c = 0; $c < $cols; $c++)
                @php $idx = $row * $cols + $c; @endphp
                <td style="width: {{ $colWidth }}; height: {{ $rowHeight }}; padding: 1.5mm; vertical-align: top;">
                    @if ($idx < $totalProducts)
                        @php $product = $productArray[$idx]; @endphp
                        <table width="100%" cellpadding="0" cellspacing="0" style="border: 1px solid #cccccc; border-collapse: collapse;">
                            {{-- Header: Name + Price --}}
                            <tr>
                                <td style="padding: {{ $cardPadding }}; background-color: #f8f9fa; border-bottom: 2px solid #2a7cc4; vertical-align: middle;">
                                    <span style="font-size: {{ $nameFontSize }}; font-weight: bold; color: #1a1a1a;">{{ $product['name'] }}</span>
                                </td>
                                <td style="padding: {{ $cardPadding }}; background-color: #f8f9fa; border-bottom: 2px solid #2a7cc4; text-align: right; vertical-align: middle; white-space: nowrap;">
                                    @if ($product['price'])
                                        <span style="font-size: {{ $priceFontSize }}; font-weight: bold; color: #2a7cc4;">{{ $product['price'] }}</span>
                                    @endif
                                </td>
                            </tr>
                            {{-- Image --}}
                            <tr>
                                <td colspan="2" style="text-align: center; padding: 4mm 2mm 3mm 2mm;">
                                    @if ($product['image_path'])
                                        <img src="{{ $product['image_path'] }}" style="height: {{ $imgHeight }};" />
                                    @else
                                        <div style="height: {{ $imgHeight }}; background-color: #f0f0f0; text-align: center; line-height: {{ $imgHeight }}; color: #aaaaaa; font-size: 10pt;">No Image</div>
                                    @endif
                                </td>
                            </tr>
                            {{-- Attributes --}}
                            @if (!empty($product['attributes']))
                                <tr>
                                    <td colspan="2" style="padding: 2mm {{ $cardPadding }} 2mm {{ $cardPadding }}; border-top: 1px solid #e0e0e0;">
                                        @foreach ($product['attributes'] as $attrName => $attrValues)
                                            <div style="font-size: {{ $detailFontSize }}; color: #333333; margin-bottom: 1mm;">
                                                <b style="color: #222222;">{{ $attrName }}:</b>
                                                <span style="color: #444444;">{{ implode(', ', $attrValues) }}</span>
                                            </div>
                                        @endforeach
                                    </td>
                                </tr>
                            @endif
                            {{-- SKU --}}
                            @if (!empty($product['sku']))
                                <tr>
                                    <td colspan="2" style="padding: 1.5mm {{ $cardPadding }}; text-align: center; border-top: 1px solid #e0e0e0; background-color: #fafafa;">
                                        <span style="font-size: {{ $skuFontSize }}; color: #999999; font-style: italic;">SKU: {{ $product['sku'] }}</span>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    @endif
                </td>
            @endfor
        </tr>
    @endfor
</table>
