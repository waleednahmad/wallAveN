@extends('frontend.layout.app')

@section('title',  $seoContent->title && !empty($seoContent->title) ? $seoContent->title : 'About Us')
@section('meta_desc', $seoContent->meta_desc && !empty($seoContent->meta_desc) ? $seoContent->meta_desc : 'About Us')
@section('keywords', $seoContent->keywords && !empty($seoContent->keywords) ? $seoContent->keywords : 'About Us')

@section('content')
    {{-- About page content lives in code (the old pages-table HTML was leftover art-auction template text). --}}
    @php
        $isSignedIn = auth('dealer')->check() || auth('representative')->check() || auth('web')->check();
    @endphp
    <style>
        .gr-about { --gr-ink: #231A14; --gr-muted: #5F534A; --gr-accent: #7B2D26; --gr-line: #E3D8C8; color: var(--gr-ink); }
        .gr-about .gr-eyebrow { font-size: 13px; letter-spacing: .16em; text-transform: uppercase; color: var(--gr-accent); font-weight: 600; margin-bottom: 14px; }
        .gr-about h1, .gr-about h2 { font-family: var(--font-playfair); color: var(--gr-ink); }
        .gr-about h1 { font-size: clamp(36px, 5vw, 60px); line-height: 1.08; margin-bottom: 20px; }
        .gr-about h2 { font-size: clamp(28px, 3.5vw, 40px); line-height: 1.15; margin-bottom: 20px; }
        .gr-about h1 em, .gr-about h2 em { color: var(--gr-accent); }
        .gr-about p { color: var(--gr-muted); font-size: 17px; line-height: 1.75; }
        .gr-about .gr-img { width: 100%; height: 100%; min-height: 320px; object-fit: cover; border-radius: 16px; }
        .gr-about .gr-card { height: 100%; border: 1px solid var(--gr-line); border-radius: 16px; padding: 28px; background: #fff; }
        .gr-about .gr-card h3 { font-size: 20px; font-weight: 600; margin: 14px 0 8px; color: var(--gr-ink); }
        .gr-about .gr-card p { font-size: 15px; margin: 0; }
        .gr-about .gr-band { background: #1F2E45; border-radius: 20px; padding: clamp(28px, 5vw, 56px); }
        .gr-about .gr-band h2, .gr-about .gr-band h3 { color: #fff; }
        .gr-about .gr-band h3 { font-family: var(--font-playfair); font-size: 24px; color: #E9C98B; margin-bottom: 8px; }
        .gr-about .gr-band p { color: #D9D3C8; font-size: 15px; margin: 0; }
        .gr-about .gr-band .col-md-4 { border-top: 1px solid rgba(255,255,255,.2); padding-top: 20px; }
        .gr-about .gr-btn { display: inline-flex; align-items: center; height: 52px; padding: 0 28px; border-radius: 26px; font-weight: 600; text-decoration: none; }
        .gr-about .gr-btn-primary { background: var(--gr-accent); color: #fff; }
        .gr-about .gr-btn-primary:hover { background: #5E211B; color: #fff; }
        .gr-about .gr-btn-outline { border: 1px solid var(--gr-ink); color: var(--gr-ink); }
        .gr-about .gr-btn-outline:hover { background: var(--gr-ink); color: #fff; }
    </style>

    <div class="gr-about">
        {{-- Intro --}}
        <section class="pt-90 pb-60">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6">
                        <div class="gr-eyebrow">About Golden Rugs</div>
                        <h1>A wholesale partner for stores that care about <em>what goes on the floor.</em></h1>
                        <p>Since 2015, Golden Rugs has supplied retailers and businesses with premium machine-made and hand-tufted rugs in an extensive range of styles and designs.</p>
                        <p class="mb-0">Beyond rugs, our curated wholesale furniture collection includes dining and living room sets, coffee tables, mirrors, vanities and TV stands — so a store can furnish a whole room from one supplier.</p>
                    </div>
                    <div class="col-lg-6">
                        <img class="gr-img" src="{{ asset('assets/img/home1/about-img1.webp') }}" alt="Golden Rugs showroom">
                    </div>
                </div>
            </div>
        </section>

        {{-- What we supply --}}
        <section class="pb-60">
            <div class="container">
                <div class="gr-band">
                    <h2 class="mb-4">What we supply</h2>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <h3>Rugs</h3>
                            <p>Machine-made and hand-tufted area rugs and runners, most designs in several sizes and colorways.</p>
                        </div>
                        <div class="col-md-4">
                            <h3>Furniture</h3>
                            <p>Dining and living room sets, coffee tables and TV stands chosen to sit well with our rugs.</p>
                        </div>
                        <div class="col-md-4">
                            <h3>Home décor</h3>
                            <p>Mirrors, vanities and finishing pieces that complete a room on the showroom floor.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- How we work --}}
        <section class="pb-60">
            <div class="container">
                <h2>How we work with retailers</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="gr-card">
                            <i class="bi bi-shield-check" style="font-size: 28px; color: #7B2D26;"></i>
                            <h3>Quality first</h3>
                            <p>Every design is chosen for how it looks and how it holds up on a busy floor.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="gr-card">
                            <i class="bi bi-grid" style="font-size: 28px; color: #7B2D26;"></i>
                            <h3>Variety that sells</h3>
                            <p>Hundreds of designs across rugs, furniture and décor, so you can stock what your customers ask for.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="gr-card">
                            <i class="bi bi-telephone" style="font-size: 28px; color: #7B2D26;"></i>
                            <h3>People you can reach</h3>
                            <p>Order online any time, and call our team at <a href="tel:+17734903801" style="color: #7B2D26; font-weight: 600;">(773) 490-3801</a> seven days a week.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Call to action --}}
        <section class="pb-90">
            <div class="container">
                <div class="row g-4 align-items-center" style="border: 1px solid #E3D8C8; border-radius: 20px; padding: clamp(24px, 4vw, 40px); margin: 0; background: #fff;">
                    <div class="col-lg-7">
                        <h2 class="mb-2">Stock your store with Golden Rugs.</h2>
                        <p class="mb-0">Questions about our range or opening an account? We're happy to help.</p>
                    </div>
                    <div class="col-lg-5 d-flex flex-wrap gap-3 justify-content-lg-end">
                        @unless ($isSignedIn)
                            <a class="gr-btn gr-btn-primary" href="{{ route('frontend.register') }}">Become a dealer</a>
                        @endunless
                        <a class="gr-btn gr-btn-outline" href="{{ route('frontend.contactUs') }}">Contact us</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
