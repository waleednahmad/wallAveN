{{-- Page not found. Standalone (no app layout): it can render before the session starts. --}}
@php
    try { $grSite = getWebsiteTitle() ?: config('app.name'); $grLogo = getMainImage(); } catch (\Throwable $e) { $grSite = config('app.name'); $grLogo = null; }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Page not found | {{ $grSite }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400&family=Inter:wght@400;500;600&display=swap">
    <link rel="stylesheet" href="{{ asset('assets/css/brand.css') }}">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px;
               background: var(--brand-bg); color: var(--brand-ink); font-family: var(--brand-font-body); }
        .nf { max-width: 520px; text-align: center; }
        .nf-logo { height: 48px; width: auto; margin-bottom: 28px; }
        .nf-code { margin: 0; font-size: 13px; font-weight: 600; letter-spacing: .18em; text-transform: uppercase; color: var(--brand-primary); }
        h1 { margin: 10px 0 12px; font-family: var(--brand-font-display); font-weight: 400; font-size: clamp(32px, 6vw, 46px); line-height: 1.1; }
        p { margin: 0 0 28px; color: var(--brand-muted); font-size: 16px; line-height: 1.6; }
        .nf-actions { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }
        .nf-btn { display: inline-flex; align-items: center; min-height: 46px; padding: 0 22px; border-radius: var(--brand-radius-control);
                  font-weight: 600; font-size: 15px; text-decoration: none; border: 1px solid var(--brand-line); color: var(--brand-ink); background: var(--brand-surface); }
        .nf-btn-primary { background: var(--brand-primary); border-color: var(--brand-primary); color: var(--brand-on-primary); }
        .nf-btn:hover { border-color: var(--brand-ink); }
        .nf-btn-primary:hover { background: var(--brand-primary-hover); border-color: var(--brand-primary-hover); }
    </style>
</head>
<body>
    <main class="nf">
        @if ($grLogo)
            <a href="{{ url('/') }}"><img src="{{ $grLogo }}" alt="{{ $grSite }}" class="nf-logo"></a>
        @endif
        <p class="nf-code">Error 404</p>
        <h1>We couldn't find that page</h1>
        <p>The link may be old, or the product is no longer in the catalog. Try the home page or browse what's in stock now.</p>
        <div class="nf-actions">
            <a class="nf-btn nf-btn-primary" href="{{ url('/') }}">Go to the home page</a>
            <a class="nf-btn" href="{{ url('/shop') }}">Browse the catalog</a>
        </div>
    </main>
</body>
</html>
