{{-- Shown to guests on /shop and product pages while the admin setting keeps the catalog for signed-in accounts.
     Replaces the old redirect to the homepage with "Shop page is disabled." --}}
@extends('frontend.layout.app')

@section('title', 'Catalog for registered retailers')
@section('meta_desc', 'Golden Rugs sells wholesale to registered retailers. Log in or apply for a dealer account to browse the catalog and see trade prices.')

@section('content')
    <section class="gr-locked">
        <div class="gr-locked-card gr-rise">
            <span class="gr-locked-icon" aria-hidden="true">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10.5" width="16" height="10" rx="2"/><path d="M8 10.5V7.5a4 4 0 018 0v3"/></svg>
            </span>
            <p class="gr-eyebrow no-rule">Wholesale only</p>
            <h1>
                @if (!empty($search))
                    Log in to search the catalog
                @elseif (($kind ?? 'shop') === 'product')
                    Log in to see this product
                @else
                    The catalog is for registered retailers
                @endif
            </h1>
            <p class="gr-locked-text">
                @if (!empty($search))
                    You searched for <strong>“{{ \Illuminate\Support\Str::limit($search, 60) }}”</strong>.
                @endif
                Our products and trade prices are available to approved retail accounts. Log in to continue, or apply for a dealer account.
            </p>
            <div class="gr-locked-actions">
                <a href="{{ route('login') }}" class="gr-btn gr-btn-primary">Log in</a>
                <a href="{{ route('frontend.register') }}" class="gr-btn gr-btn-outline">Apply for a dealer account</a>
            </div>
            <p class="gr-locked-help">Questions? Call <a href="tel:+17734903801" class="gr-link">(773) 490-3801</a> or <a href="{{ route('frontend.contactUs') }}" class="gr-link">send us a message</a>.</p>
        </div>
    </section>
@endsection
