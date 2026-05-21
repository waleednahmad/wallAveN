{{--
    pdf-page.blade.php — "Framed editorial" catalog page.

    The container effect comes from a two-layer card:
      OUTER cell  →  warm stone-50 tray with a hairline border  (the frame)
      INNER card  →  white card with image, title, specs, SKU    (the content)

    Each product carries a small \u2116N index badge above the title
    so the catalog feels like a curated coffee-table book.

    Page geometry (must match the Mpdf margins set by the job):
        body usable height \u2248 242 mm \u2192 3 fixed rows of 79 mm
--}}
@php
    $totalProducts   = count($productArray);
    $startIndex      = $startIndex      ?? 1;
    $totalProductsAll = $totalProducts ?? 0;

    $rowHeight = '79mm';
    $colWidth  = $cols === 3 ? '33.33%' : '50%';

    if ($layout === '3x3') {
        $imgBoxH         = '36mm';
        $titleSize       = '8.5pt';
        $priceSize       = '8.5pt';
        $indexSize       = '5.5pt';
        $attrKeySize     = '5.5pt';
        $attrValSize     = '6.8pt';
        $skuSize         = '6.5pt';
        $cardPad         = '2mm';
        $trayPad         = '1mm';
        $maxAttrLines    = 3;
        $titleMaxChars   = 24;
        $attrValMaxChars = 30;
    } else {
        $imgBoxH         = '46mm';
        $titleSize       = '11pt';
        $priceSize       = '11pt';
        $indexSize       = '6.5pt';
        $attrKeySize     = '6.5pt';
        $attrValSize     = '8pt';
        $skuSize         = '6.8pt';
        $cardPad         = '3mm';
        $trayPad         = '1.5mm';
        $maxAttrLines    = 4;
        $titleMaxChars   = 36;
        $attrValMaxChars = 60;
    }
@endphp

<table width="100%" cellpadding="0" cellspacing="0"
       style="border-collapse: separate; border-spacing: 3.5mm 3.5mm;">
    @for ($row = 0; $row < $rows; $row++)
        <tr>
            @for ($c = 0; $c < $cols; $c++)
                @php
                    $idx = $row * $cols + $c;
                    $absoluteIndex = $startIndex + $idx;
                @endphp
                <td width="{{ $colWidth }}" height="{{ $rowHeight }}" valign="top" style="padding: 0;">
                    @if ($idx < $totalProducts)
                        @php $product = $productArray[$idx]; @endphp

                        {{-- ============= OUTER TRAY (the container) ============= --}}
                        <table width="100%" cellpadding="0" cellspacing="0"
                               style="border-collapse: collapse;
                                      background: #fafaf9;
                                      border: 0.25mm solid #e7e5e4;">
                            <tr>
                                <td style="padding: {{ $trayPad }};">

                                    {{-- ============= INNER CARD ============= --}}
                                    <table width="100%" cellpadding="0" cellspacing="0"
                                           style="border-collapse: collapse;
                                                  background: #ffffff;
                                                  border: 0.2mm solid #e7e5e4;">

                                        {{-- Image block --}}
                                        <tr>
                                            <td height="{{ $imgBoxH }}" align="center" valign="middle"
                                                style="background: #f5f5f4; padding: 0;
                                                       border-bottom: 0.2mm solid #e7e5e4;">
                                                @if (!empty($product['image_path']))
                                                    <img src="{{ $product['image_path'] }}"
                                                         style="max-height: {{ $imgBoxH }}; max-width: 100%;" />
                                                @else
                                                    <span style="color: #a8a29e; font-size: 7.5pt; font-style: italic;">
                                                        No image
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>

                                        {{-- \u2116 index badge + meta strip --}}
                                        <tr>
                                            <td style="padding: 1.4mm {{ $cardPad }} 0.6mm;">
                                                <table width="100%" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td style="padding: 0; vertical-align: middle;">
                                                            <span style="font-family: 'DejaVu Sans Mono', monospace;
                                                                         font-size: {{ $indexSize }};
                                                                         color: #92400e;
                                                                         letter-spacing: 0.8pt;
                                                                         text-transform: uppercase;
                                                                         font-weight: bold;">
                                                                N&deg; {{ str_pad((string) $absoluteIndex, 2, '0', STR_PAD_LEFT) }}
                                                            </span>
                                                            <span style="color: #d6d3d1;">&nbsp;&mdash;&nbsp;</span>
                                                            <span style="font-size: {{ $indexSize }};
                                                                         color: #a8a29e;
                                                                         letter-spacing: 0.5pt;
                                                                         text-transform: uppercase;">
                                                                Item
                                                            </span>
                                                        </td>
                                                        @if (!empty($product['sku']))
                                                            <td style="padding: 0; text-align: right; vertical-align: middle;">
                                                                <span style="font-family: 'DejaVu Sans Mono', monospace;
                                                                             font-size: {{ $skuSize }};
                                                                             color: #78716c;
                                                                             letter-spacing: 0.4pt;">
                                                                    SKU&nbsp;{{ $product['sku'] }}
                                                                </span>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>

                                        {{-- Title + price row --}}
                                        <tr>
                                            <td style="padding: 0 {{ $cardPad }} 0.6mm;">
                                                <table width="100%" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td style="padding: 0; vertical-align: middle;">
                                                            <span style="font-size: {{ $titleSize }};
                                                                         font-weight: bold;
                                                                         color: #1c1917;
                                                                         line-height: 1.18;">
                                                                {{ \Illuminate\Support\Str::limit($product['name'] ?? '', $titleMaxChars) }}
                                                            </span>
                                                        </td>
                                                        <td style="padding: 0; text-align: right;
                                                                   white-space: nowrap; vertical-align: middle;">
                                                            @if (!empty($product['price']))
                                                                <span style="font-size: {{ $priceSize }};
                                                                             font-weight: bold;
                                                                             color: #92400e;">
                                                                    {{ $product['price'] }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>

                                        {{-- Walnut divider --}}
                                        @if (!empty($product['attributes']))
                                            <tr>
                                                <td style="padding: 0 {{ $cardPad }};">
                                                    <table width="100%" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td width="6mm" style="border-top: 0.4mm solid #92400e;
                                                                       font-size: 0; line-height: 0; padding: 0;">&nbsp;</td>
                                                            <td style="border-top: 0.2mm solid #e7e5e4;
                                                                       font-size: 0; line-height: 0; padding: 0;">&nbsp;</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        @endif

                                        {{-- Spec sheet --}}
                                        @if (!empty($product['attributes']))
                                            <tr>
                                                <td style="padding: 1.4mm {{ $cardPad }} 1.6mm;">
                                                    <table width="100%" cellpadding="0" cellspacing="0">
                                                        @php $shown = 0; @endphp
                                                        @foreach ($product['attributes'] as $attrName => $attrValues)
                                                            @if ($shown >= $maxAttrLines) @break @endif
                                                            <tr>
                                                                <td width="22%" style="padding: 0 0 0.5mm 0; vertical-align: top;">
                                                                    <span style="font-size: {{ $attrKeySize }};
                                                                                 font-weight: bold;
                                                                                 color: #92400e;
                                                                                 letter-spacing: 0.7pt;
                                                                                 text-transform: uppercase;">
                                                                        {{ $attrName }}
                                                                    </span>
                                                                </td>
                                                                <td style="padding: 0 0 0.5mm 0; vertical-align: top;">
                                                                    <span style="font-size: {{ $attrValSize }};
                                                                                 color: #44403c;">
                                                                        {{ \Illuminate\Support\Str::limit(implode(', ', (array) $attrValues), $attrValMaxChars) }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            @php $shown++; @endphp
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                        @endif

                                    </table>{{-- /inner card --}}

                                </td>
                            </tr>
                        </table>{{-- /outer tray --}}
                    @endif
                </td>
            @endfor
        </tr>
    @endfor
</table>
