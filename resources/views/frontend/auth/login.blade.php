@extends('frontend.layout.app')

@section('title', 'Sign in')

@section('content')
    <div class="gr-auth">
        <div class="gr-auth-visual">
            <img src="{{ asset('assets/img/home1/feature-img.webp') }}" alt="">
            <div class="gr-auth-copy">
                <h2>Your showroom's <em>next bestseller</em> is already in stock.</h2>
                <p>Sign in to see trade prices, reorder in a few clicks and track every order.</p>
            </div>
        </div>

        <div class="gr-auth-form">
            <div class="gr-rise">
                <h1>Welcome back</h1>
                <p style="color: var(--gr-muted); margin: 0 0 32px;">Sign in to your dealer or sales rep account.</p>

                <form action="{{ route('frontend.submitLogin') }}" method="POST" class="d-flex flex-column" style="gap: 18px;">
                    @csrf
                    <div>
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required autocomplete="email"
                            value="{{ old('email', request()->get('email')) }}" placeholder="you@yourstore.com">
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-baseline">
                            <label for="password" class="form-label">Password</label>
                            <a href="{{ route('password.request') }}" class="gr-link" style="font-size: 14px; font-weight: 500;">Forgot password?</a>
                        </div>
                        <div style="position: relative;">
                            <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password" style="padding-right: 72px;">
                            <button type="button" id="grTogglePw" aria-label="Show password" aria-controls="password"
                                style="position: absolute; right: 4px; top: 2px; height: 44px; padding: 0 12px; border: 0; background: transparent; font-size: 13px; font-weight: 600; color: var(--gr-muted);">Show</button>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger mb-0" role="alert">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger mb-0" role="alert">{{ session('error') }}</div>
                    @endif

                    <button type="submit" class="gr-btn gr-btn-primary w-100" style="margin-top: 6px;">Sign in</button>
                </form>

                <div class="gr-divider">New to Golden Rugs?</div>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <a href="{{ route('frontend.register') }}" class="gr-choice"><strong>Become a dealer</strong><span>Buy at trade prices for your store</span></a>
                    </div>
                    @if (showBecomeARepInMenu())
                        <div class="col-sm-6">
                            <a href="{{ route('representative.register') }}" class="gr-choice"><strong>Join as a sales rep</strong><span>Sell Golden Rugs to retailers</span></a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            var btn = document.getElementById('grTogglePw');
            var input = document.getElementById('password');
            if (!btn || !input) return;
            btn.addEventListener('click', function () {
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.textContent = show ? 'Hide' : 'Show';
                btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
            });
        })();
    </script>
@endpush
