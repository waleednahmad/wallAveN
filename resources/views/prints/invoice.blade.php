{{--
    Order invoice: one template for the browser print page and the downloadable PDF (DomPDF).
    DomPDF has no flexbox/grid and only its own fonts, so the layout is tables and floats;
    the browser gets the brand fonts on top. Print friendly: white page, thin rules, colour only as accents.

    Expects: $order (with dealer, orderItems), $forPdf (bool), optional $logoImage (data URI for the PDF),
    optional $pdfUrl / $backUrl for the browser toolbar.
--}}
@php
    $forPdf = $forPdf ?? false;
    $dealer = $order->dealer;
    $site = getWebsiteTitle() ?: config('app.name');
    $money = fn ($n) => '$' . number_format((float) $n, 2);

    // Images: file paths for DomPDF (no network), URLs in the browser
    $img = function (?string $path) use ($forPdf) {
        if (!$path || !file_exists(public_path($path))) {
            return null;
        }
        // Browser: the 240px thumbnail; PDF: a small JPEG (keeps big orders light and quick)
        return $forPdf ? \App\Support\Invoice::pdfImage($path) : thumb($path, 240);
    };
    $logo = $forPdf ? ($logoImage ?? null) : getMainImage();

    $statusTone = [
        'pending' => ['#8A6116', '#F6E7C8'],
        'processing' => ['#2B4466', '#DCE6F2'],
        'completed' => ['#2F7A4A', '#DDEFE3'],
        'declined' => ['#A3392E', '#F6DEDA'],
        'canceled' => ['#5F534A', '#EAE5DE'],
    ][$order->status] ?? ['#5F534A', '#EAE5DE'];

    $placedBy = $order->representative_id ? 'Sales rep' : ($order->admin_id ? 'Our team' : 'Online');

    $addressLines = array_values(array_filter([
        $dealer?->address,
        trim(implode(', ', array_filter([$dealer?->city, trim(($dealer?->state ?? '') . ' ' . ($dealer?->zip_code ?? ''))]))),
    ]));

    $items = $order->orderItems;
    $units = (int) $items->sum('quantity');
    $subtotal = (float) $items->sum('total');
    $discount = (float) ($order->discount ?? 0);
    $shipping = (float) ($order->shipping ?? 0);
    $tax = (float) ($order->tax ?? 0);
    $total = (float) $order->total;

    // "Size 5' x 8' · Color Beige" without the placeholder "None" values
    $specs = function ($item) {
        $attrs = is_array($item->attributes) ? $item->attributes : (json_decode((string) $item->attributes, true) ?: []);
        $parts = [];
        foreach ($attrs as $k => $v) {
            if ($v === null || $v === '' || strtolower((string) $v) === 'none' || strtolower((string) $k) === 'none') {
                continue;
            }
            $parts[] = ucwords((string) $k) . ' ' . str_replace('"', "'", (string) $v);
        }
        return implode('  ·  ', $parts);
    };
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }} · {{ $site }}</title>
    @unless ($forPdf)
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500&family=Inter:wght@400;500;600&display=swap">
    @endunless
    <style>
        @page { margin: 34px 38px 56px; }
        * { box-sizing: border-box; }
        body {
            margin: 0; color: #231A14; background: #fff;
            font-family: {!! $forPdf ? 'Helvetica, Arial, sans-serif' : '"Inter", Helvetica, Arial, sans-serif' !!};
            font-size: 11px; line-height: 1.45;
        }
        .display { font-family: {!! $forPdf ? 'times, serif' : '"Fraunces", Georgia, serif' !!}; font-weight: 400; }
        table { border-collapse: collapse; width: 100%; }
        td, th { vertical-align: top; padding: 0; }
        .muted { color: #5F534A; }
        .label { font-size: 8.5px; font-weight: bold; letter-spacing: 1.4px; text-transform: uppercase; color: #8C8178; }
        .num { text-align: right; white-space: nowrap; }

        .sheet { max-width: {{ $forPdf ? 'none' : '820px' }}; margin: 0 auto; }
        .rule { height: 3px; background: #7B2D26; margin-bottom: 22px; }

        /* Header */
        .head td { vertical-align: top; }
        .logo { max-height: 62px; max-width: 190px; }
        .seller { margin-top: 8px; font-size: 10px; line-height: 1.55; color: #5F534A; }
        .seller strong { color: #231A14; font-size: 11px; }
        .doc-title { font-size: 34px; line-height: 1; color: #231A14; margin: 0 0 10px; text-align: right; }
        .meta td { padding: 3px 0; font-size: 10.5px; }
        .meta td.k { color: #8C8178; text-align: right; padding-right: 12px; white-space: nowrap; }
        .meta td.v { text-align: right; font-weight: bold; white-space: nowrap; width: 1%; }
        .pill { display: inline-block; padding: 2px 9px; border-radius: 10px; font-size: 9px; font-weight: bold; letter-spacing: .6px; text-transform: uppercase; }

        /* Parties */
        .parties { margin-top: 26px; }
        .party { border-top: 1px solid #E3D8C8; padding-top: 10px; }
        .party-name { font-size: 13px; font-weight: bold; margin: 4px 0 2px; }
        .party p { margin: 0; color: #5F534A; font-size: 10.5px; }

        /* Items */
        .items { margin-top: 26px; }
        .items th { padding: 8px 8px; font-size: 8.5px; font-weight: bold; letter-spacing: 1.2px; text-transform: uppercase; color: #5F534A; border-bottom: 1.5px solid #231A14; text-align: left; }
        .items td { padding: 9px 8px; border-bottom: 1px solid #EFE6D8; vertical-align: middle; }
        .items tr.alt td { background: #FBF8F3; }
        .items .idx { width: 22px; color: #8C8178; font-size: 10px; }
        .items .thumb { width: 46px; }
        .items .thumb img { width: 40px; height: 40px; border-radius: 6px; border: 1px solid #E3D8C8; }
        .item-name { font-weight: bold; font-size: 11px; }
        .item-meta { color: #5F534A; font-size: 9.5px; margin-top: 2px; }
        .items .qty { width: 46px; text-align: center; }
        .items .price { width: 78px; }
        .items .amount { width: 86px; font-weight: bold; }
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }

        /* Totals + notes */
        .after { margin-top: 18px; }
        .notes { padding-right: 26px; }
        .note { margin-bottom: 12px; }
        .note p { margin: 3px 0 0; font-size: 10.5px; color: #231A14; }
        .totals td { padding: 5px 0; font-size: 11px; }
        .totals td.k { color: #5F534A; }
        .totals tr.grand td { background: #F5EFE6; border-top: 1.5px solid #7B2D26; padding: 11px 12px; font-size: 15px; font-weight: bold; color: #231A14; }
        .totals tr.grand td.v { color: #7B2D26; font-size: 17px; }
        .totals td.k { padding-left: 12px; }
        .totals td.v { padding-right: 12px; }

        .thanks { margin-top: 34px; padding-top: 14px; border-top: 1px solid #E3D8C8; }
        .thanks .display { font-size: 17px; margin: 0 0 3px; }
        .thanks p { margin: 0; color: #5F534A; font-size: 10px; }

        /* PDF: repeating footer with page numbers */
        .pdf-foot .pg:after { content: "Page " counter(page); }
        .pdf-foot { position: fixed; bottom: -36px; left: 0; right: 0; font-size: 8.5px; color: #8C8178; border-top: 1px solid #EFE6D8; padding-top: 6px; }

        /* Browser only */
        .toolbar { position: sticky; top: 0; z-index: 2; background: #F5EFE6; border-bottom: 1px solid #E3D8C8; padding: 10px 16px; }
        .toolbar-in { max-width: 820px; margin: 0 auto; display: flex; gap: 8px; align-items: center; }
        .toolbar-title { margin-right: auto; font-size: 13px; color: #5F534A; }
        .btn { display: inline-flex; align-items: center; gap: 6px; min-height: 36px; padding: 0 16px; border-radius: 10px; border: 1px solid #E3D8C8; background: #FFFCF7; color: #231A14; font: 600 13px "Inter", Arial, sans-serif; text-decoration: none; cursor: pointer; }
        .btn:hover { border-color: #231A14; }
        .btn-primary { background: #7B2D26; border-color: #7B2D26; color: #fff; }
        .btn-primary:hover { background: #5E211B; border-color: #5E211B; }
        .screen-wrap { background: #EDE6DB; padding: 28px 16px 48px; min-height: 100vh; }
        .screen-wrap .sheet { background: #fff; padding: 44px 48px 40px; box-shadow: 0 10px 40px rgba(35, 26, 20, .12); border-radius: 4px; }
        @media print {
            .toolbar { display: none !important; }
            .screen-wrap { background: #fff; padding: 0; }
            .screen-wrap .sheet { box-shadow: none; padding: 0; max-width: none; border-radius: 0; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
        @media (max-width: 640px) {
            .screen-wrap .sheet { padding: 24px 18px; }
            .doc-title { font-size: 26px; }
        }
    </style>
</head>
<body>
    @unless ($forPdf)
        <div class="toolbar">
            <div class="toolbar-in">
                <span class="toolbar-title">Invoice #{{ $order->id }}</span>
                @if (!empty($backUrl))
                    <a class="btn" href="{{ $backUrl }}">Back</a>
                @endif
                @if (!empty($pdfUrl))
                    <a class="btn" href="{{ $pdfUrl }}">Download PDF</a>
                @endif
                <button type="button" class="btn btn-primary" onclick="window.print()">Print</button>
            </div>
        </div>
        <div class="screen-wrap">
    @else
        <div class="pdf-foot">
            <table><tr>
                <td>{{ $site }} · Invoice #{{ $order->id }}</td>
                <td style="text-align: center;" class="pg"></td>
                <td class="num">{{ trim(implode(' · ', array_filter([companyPhone(), companyEmail()]))) }}</td>
            </tr></table>
        </div>
    @endunless

    <div class="sheet">
        <div class="rule"></div>

        {{-- Seller + document details --}}
        <table class="head">
            <tr>
                <td style="width: 55%;">
                    @if ($logo)
                        <img src="{{ $logo }}" alt="{{ $site }}" class="logo">
                    @endif
                    <div class="seller">
                        <strong>{{ $site }}</strong><br>
                        @if (companyAddress()){{ companyAddress() }}<br>@endif
                        {{ trim(implode(' · ', array_filter([companyPhone(), companyEmail()]))) }}
                    </div>
                </td>
                <td style="width: 45%;">
                    <h1 class="doc-title display">Invoice</h1>
                    <table class="meta">
                        <tr><td class="k">Invoice no.</td><td class="v">#{{ $order->id }}</td></tr>
                        <tr><td class="k">Date</td><td class="v">{{ $order->created_at?->format('M j, Y') }}</td></tr>
                        @if ($order->po_number)
                            <tr><td class="k">PO number</td><td class="v">{{ $order->po_number }}</td></tr>
                        @endif
                        <tr><td class="k">Placed</td><td class="v">{{ $placedBy }}</td></tr>
                        <tr>
                            <td class="k">Status</td>
                            <td class="v"><span class="pill" style="color: {{ $statusTone[0] }}; background: {{ $statusTone[1] }};">{{ ucfirst($order->status) }}</span></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        {{-- Bill to / ship to --}}
        <table class="parties">
            <tr>
                <td style="width: 48%;" class="party">
                    <div class="label">Bill to</div>
                    <div class="party-name">{{ $dealer?->company_name ?: ($dealer?->name ?: '—') }}</div>
                    @if ($dealer?->name && $dealer?->company_name)<p>{{ $dealer->name }}</p>@endif
                    @foreach ($addressLines as $line)<p>{{ $line }}</p>@endforeach
                </td>
                <td style="width: 4%;"></td>
                <td style="width: 48%;" class="party">
                    <div class="label">Ship to</div>
                    <div class="party-name">{{ $dealer?->company_name ?: ($dealer?->name ?: '—') }}</div>
                    @foreach ($addressLines as $line)<p>{{ $line }}</p>@endforeach
                    @if ($dealer?->phone || $dealer?->email)
                        <p>{{ trim(implode(' · ', array_filter([$dealer?->phone, $dealer?->email]))) }}</p>
                    @endif
                </td>
            </tr>
        </table>

        {{-- Line items --}}
        <table class="items">
            <thead>
                <tr>
                    <th class="idx">#</th>
                    <th class="thumb"></th>
                    <th>Item</th>
                    <th class="qty" style="text-align: center;">Qty</th>
                    <th class="price num">Unit price</th>
                    <th class="amount num">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    @php $src = $img($item->displayImage()); $spec = $specs($item); @endphp
                    <tr @class(['alt' => $loop->even])>
                        <td class="idx">{{ $loop->iteration }}</td>
                        <td class="thumb">
                            @if ($src)<img src="{{ $src }}" alt="">@endif
                        </td>
                        <td>
                            <div class="item-name">{{ $item->name ?: '—' }}</div>
                            <div class="item-meta">
                                @if ($item->sku)SKU {{ $item->sku }}@endif{{ $item->sku && $spec ? '  ·  ' : '' }}{{ $spec }}
                            </div>
                        </td>
                        <td class="qty">{{ number_format((int) $item->quantity) }}</td>
                        <td class="price num">{{ $money($item->price) }}</td>
                        <td class="amount num">{{ $money($item->total) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Notes + totals --}}
        <table class="after">
            <tr>
                <td style="width: 56%;" class="notes">
                    @if ($order->more_info)
                        <div class="note">
                            <div class="label">Notes</div>
                            <p>{{ $order->more_info }}</p>
                        </div>
                    @endif
                    <div class="note">
                        <div class="label">Summary</div>
                        <p>{{ $items->count() }} {{ \Illuminate\Support\Str::plural('line', $items->count()) }} · {{ number_format($units) }} {{ \Illuminate\Support\Str::plural('unit', $units) }}</p>
                    </div>
                </td>
                <td style="width: 44%;">
                    <table class="totals">
                        <tr><td class="k">Subtotal</td><td class="v num">{{ $money($subtotal) }}</td></tr>
                        @if ($discount > 0)
                            <tr><td class="k">Discount</td><td class="v num">−{{ $money($discount) }}</td></tr>
                        @endif
                        @if ($shipping > 0)
                            <tr><td class="k">Shipping</td><td class="v num">{{ $money($shipping) }}</td></tr>
                        @endif
                        @if ($tax > 0)
                            <tr><td class="k">Tax</td><td class="v num">{{ $money($tax) }}</td></tr>
                        @endif
                        <tr class="grand"><td class="k">Total</td><td class="v num">{{ $money($total) }}</td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="thanks">
            <p class="display">Thank you for your order.</p>
            <p>Questions about this invoice? {{ trim(implode(' · ', array_filter([companyPhone() ? 'Call ' . companyPhone() : null, companyEmail()]))) }}</p>
        </div>
    </div>

    @unless ($forPdf)
        </div>
    @endunless

</body>
</html>
