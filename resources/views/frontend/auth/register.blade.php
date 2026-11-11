@extends('frontend.layout.app')

@section('title', 'Become a dealer')

@section('content')
    <div class="gr-container" style="padding-top: 48px; padding-bottom: 80px;">
        <div class="d-flex flex-wrap align-items-start" style="gap: 48px;">

            {{-- Why + what happens next --}}
            <aside style="flex: 1 1 320px; max-width: 400px; min-width: 0;">
                <p class="gr-eyebrow no-rule">Dealer application</p>
                <h1 class="gr-h2" style="margin-bottom: 16px;">Open your trade account</h1>
                <p style="color: var(--gr-muted); line-height: 1.6; margin: 0 0 32px;">Takes about five minutes. Once approved you can see trade prices and order online.</p>
                <div class="gr-next">
                    <h2>What happens next</h2>
                    <ol>
                        <li><b>1</b><span>You submit this application</span></li>
                        <li><b>2</b><span>Our team reviews your business details and resale certificate</span></li>
                        <li><b>3</b><span>We email you when your account is approved and your price list is ready</span></li>
                    </ol>
                    <p style="margin: 22px 0 0; padding-top: 18px; border-top: 1px solid rgba(241,230,210,.2); font-size: 14px; color: #C9C2B6;">Questions? Call <a href="tel:+17734903801" style="color: #fff; font-weight: 600;">(773) 490-3801</a></p>
                </div>
            </aside>

            {{-- Form --}}
            <div class="gr-card" style="flex: 999 1 560px; min-width: 0; padding: clamp(24px, 4vw, 40px);">
                <form action="{{ route('frontend.submitRegister') }}" method="POST" id="demo-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="ref" name="ref" value="{{ request()->get('ref') }}">

                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert" style="margin-bottom: 24px;">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- 1. Business --}}
                    <section class="gr-form-section">
                        <h2><span class="gr-step-dot">1</span>Your business</h2>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="company_name" class="form-label">Company name <span class="gr-required">*</span></label>
                                <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name') }}" required autocomplete="organization">
                            </div>
                            <div class="col-md-6">
                                <label for="business_type" class="form-label">Business type <span class="gr-required">*</span></label>
                                <input type="text" class="form-control" id="business_type" name="business_type" value="{{ old('business_type') }}" required placeholder="e.g. Furniture store">
                                <div class="d-flex flex-wrap" style="gap: 6px; margin-top: 8px;">
                                    @foreach (['Furniture store', 'Rug store', 'Online retailer', 'Interior designer'] as $type)
                                        <button type="button" class="gr-type-chip" data-value="{{ $type }}" style="height: 32px; padding: 0 10px; border: 1px solid var(--gr-line); border-radius: 16px; background: var(--gr-linen); font-size: 12px; color: var(--gr-ink);">{{ $type }}</button>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="years_in_business" class="form-label">Years in business <span class="gr-required">*</span></label>
                                <input type="number" min="0" class="form-control" id="years_in_business" name="years_in_business" value="{{ old('years_in_business') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="tax_id" class="form-label">Tax ID <span class="gr-required">*</span></label>
                                <input type="text" class="form-control" id="tax_id" name="tax_id" value="{{ old('tax_id') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="website" class="form-label">Website <span style="font-weight: 400; color: var(--gr-muted);">(optional)</span></label>
                                <input type="text" class="form-control" id="website" name="website" value="{{ old('website') }}" placeholder="https://">
                            </div>
                            <div class="col-12">
                                <label for="address" class="form-label">Store address <span class="gr-required">*</span></label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" required autocomplete="street-address">
                            </div>
                            <div class="col-md-4">
                                <label for="city" class="form-label">City <span class="gr-required">*</span></label>
                                <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="state" class="form-label">State <span class="gr-required">*</span></label>
                                <input type="text" class="form-control" id="state" name="state" value="{{ old('state') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="zip_code" class="form-label">Zip code <span class="gr-required">*</span></label>
                                <input type="text" inputmode="numeric" class="form-control" id="zip_code" name="zip_code" value="{{ old('zip_code') }}" required>
                            </div>
                        </div>
                    </section>

                    {{-- 2. Contact & sign-in --}}
                    <section class="gr-form-section">
                        <h2><span class="gr-step-dot">2</span>Contact &amp; sign-in</h2>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="name" class="form-label">Your full name <span class="gr-required">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autocomplete="name">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="gr-required">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                                <p class="gr-help">You'll sign in with this email.</p>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone <span class="gr-required">*</span></label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required autocomplete="tel">
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password <span class="gr-required">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required autocomplete="new-password">
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm password <span class="gr-required">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                    </section>

                    {{-- 3. Documents --}}
                    <section class="gr-form-section">
                        <h2><span class="gr-step-dot">3</span>Documents</h2>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="resale_certificate" class="form-label">Resale certificate <span style="font-weight: 400; color: var(--gr-muted);">(optional, speeds up approval)</span></label>
                                <input type="file" class="form-control" id="resale_certificate" name="resale_certificate" accept="application/pdf,image/*">
                                <p class="gr-help">PDF or image.</p>
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label">Anything we should know? <span style="font-weight: 400; color: var(--gr-muted);">(optional)</span></label>
                                <textarea class="form-control" rows="4" id="message" name="message" placeholder="Products you're interested in, expected order volume, how you heard about us…">{{ old('message') }}</textarea>
                            </div>
                            <div class="col-12">
                                <div class="g-recaptcha" data-sitekey="{{ get_recaptcha_site_key() }}"></div>
                                @if ($errors->has('g-recaptcha-response'))
                                    <p class="text-danger mt-2 mb-0">{{ $errors->first('g-recaptcha-response') }}</p>
                                @endif
                            </div>
                        </div>
                    </section>

                    <div class="d-flex flex-wrap justify-content-between align-items-center" style="gap: 16px; margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--gr-line-soft);">
                        <p class="mb-0" style="font-size: 14px; color: var(--gr-muted);">Already have an account? <a href="{{ route('login') }}" class="gr-link">Sign in</a></p>
                        <button type="submit" class="gr-btn gr-btn-primary">Submit application</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Business type shortcuts fill the free-text field (it stays free text).
        document.querySelectorAll('.gr-type-chip').forEach(function (chip) {
            chip.addEventListener('click', function () {
                var input = document.getElementById('business_type');
                if (input) { input.value = chip.getAttribute('data-value'); input.focus(); }
            });
        });
    </script>
@endpush

@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCAAWB_WeAxjCYM_vtNn30Oqo-SZP3cYDE&libraries=places">
    </script>

    <script>
        function onSubmit(token) {
            alert('test');
            document.getElementById("demo-form").submit();
        }
        $(document).ready(function() {

            // Prevent negative values in years in business field
            $('#years_in_business').on('input', function() {
                if ($(this).val() < 0) {
                    $(this).val(0);
                }
            });
        });


        function initAutocomplete() {
            const addressInput = document.getElementById("address");
            const autocomplete = new google.maps.places.Autocomplete(addressInput, {
                types: ["geocode"],
                componentRestrictions: {
                    country: "us"
                } // Restrict to US (remove if not needed)
            });

            autocomplete.addListener("place_changed", function() {
                const place = autocomplete.getPlace();
                let address = "";
                let city = "";
                let state = "";
                let zip = "";

                if (!place.address_components) {
                    return;
                }

                for (const component of place.address_components) {
                    const componentType = component.types[0];

                    switch (componentType) {
                        case "street_number":
                            address = component.long_name + " ";
                            break;
                        case "route":
                            address += component.long_name;
                            break;
                        case "locality":
                            city = component.long_name;
                            break;
                        case "administrative_area_level_1":
                            state = component.short_name;
                            break;
                        case "postal_code":
                            zip = component.long_name;
                            break;
                    }
                }

                // document.getElementById("address").value = address;
                document.getElementById("city").value = city;
                document.getElementById("state").value = state;
                document.getElementById("zip_code").value = zip;
            });
        }
        window.onload = initAutocomplete;


        document.addEventListener("DOMContentLoaded", function() {
            const phoneInput = document.getElementById("phone");

            phoneInput.addEventListener("input", function(event) {
                let numbers = phoneInput.value.replace(/\D/g, ""); // Remove all non-numeric characters

                if (numbers.length > 10) {
                    numbers = numbers.substring(0, 10); // Limit input to 10 digits
                }

                let formattedNumber = "";
                if (numbers.length > 6) {
                    formattedNumber =
                        `(${numbers.substring(0, 3)}) ${numbers.substring(3, 6)}-${numbers.substring(6)}`;
                } else if (numbers.length > 3) {
                    formattedNumber = `(${numbers.substring(0, 3)}) ${numbers.substring(3)}`;
                } else if (numbers.length > 0) {
                    formattedNumber = `(${numbers}`;
                }

                phoneInput.value = formattedNumber;
            });
        });




        // For the address field
        // - make sure to remove any thing after the comma while writing the address
        $('#address').on('blur', function() {
            let address = $(this).val();
            let addressArr = address.split(',');
            $(this).val(addressArr[0]);
        });
        // or when click outside the input field
        $('#address').on('focusout', function() {
            let address = $(this).val();
            let addressArr = address.split(',');
            $(this).val(addressArr[0]);
        });
    </script>
@endpush
