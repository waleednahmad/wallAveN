@extends('frontend.layout.app')

@section('title', 'Become a sales rep')

@section('content')
    <div class="gr-container" style="padding-top: 48px; padding-bottom: 80px;">
        <div class="d-flex flex-wrap align-items-start" style="gap: 48px;">
            <aside style="flex: 1 1 320px; max-width: 400px; min-width: 0;">
                <p class="gr-eyebrow no-rule">Sales rep program</p>
                <h1 class="gr-h2" style="margin-bottom: 16px;">Sell Golden Rugs to retailers</h1>
                <p style="color: var(--gr-muted); line-height: 1.6; margin: 0 0 28px;">Place orders for the dealers you manage from one account and see every order you placed.</p>
                <div class="gr-secure">
                    <i class="bi bi-lock" aria-hidden="true" style="font-size: 18px;"></i>
                    <span>Tax and bank details are only used to pay you and to file tax forms. They're sent over a secure connection.</span>
                </div>
                <p style="margin: 20px 0 0; font-size: 14px; color: var(--gr-muted);">Questions? Call <a href="tel:+17734903801" class="gr-link">(773) 490-3801</a></p>
            </aside>

            <div class="gr-card" style="flex: 999 1 560px; min-width: 0; padding: clamp(24px, 4vw, 40px);">
                <form class="row g-4" action="{{ route('representative.submitRegister') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label for="name" class="form-label">Name
                                        <span class="text-danger">*</span>

                                    </label>
                                    <input type="text" class="form-control" id="name" name="name" required
                                        value="{{ old('name') }}">
                                </div>
                            </div>
                            {{-- wmail --}}
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label for="email" class="form-label">Email
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" required
                                        value="{{ old('email') }}">
                                </div>
                            </div>

                            {{-- phone --}}
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label for="phone" class="form-label">Phone
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="phone" name="phone" required
                                        value="{{ old('phone') }}">
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label for="bussiness_name" class="form-label">Business Name
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="bussiness_name" name="bussiness_name"
                                        value="{{ old('bussiness_name') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label for="faderal_tax_classification" class="form-label">
                                        Federal Tax Classification
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="faderal_tax_classification"
                                        name="faderal_tax_classification">
                                        <option value="individual"
                                            {{ old('faderal_tax_classification') == 'individual' ? 'selected' : '' }}>
                                            Individual</option>
                                        <option value="c_corporation"
                                            {{ old('faderal_tax_classification') == 'c_corporation' ? 'selected' : '' }}>
                                            C Corporation</option>
                                        <option value="s_corporation"
                                            {{ old('faderal_tax_classification') == 's_corporation' ? 'selected' : '' }}>
                                            S Corporation</option>
                                        <option value="partnership"
                                            {{ old('faderal_tax_classification') == 'partnership' ? 'selected' : '' }}>
                                            Partnership</option>
                                        <option value="trust"
                                            {{ old('faderal_tax_classification') == 'trust' ? 'selected' : '' }}>Trust
                                        </option>
                                        <option value="limited_liability_company"
                                            {{ old('faderal_tax_classification') == 'limited_liability_company' ? 'selected' : '' }}>
                                            Limited Liability Company</option>
                                        <option value="other"
                                            {{ old('faderal_tax_classification') == 'other' ? 'selected' : '' }}>Other
                                        </option>
                                    </select>
                                </div>
                            </div>
                            {{-- other_info --}}
                            <div class="col-md-6" id="other_info_container">
                                <div class="mb-1">
                                    <label for="other_info" class="form-label">Other Info
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" id="other_info" name="other_info" rows="4">{{ old('other_info') }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label for="address" class="form-label">Address
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        value="{{ old('address') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label for="city" class="form-label">City
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="city" name="city"
                                        value="{{ old('city') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label for="state" class="form-label">State
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="state" name="state"
                                        value="{{ old('state') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label for="zip_code" class="form-label">Zip Code
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="zip_code" name="zip_code"
                                        value="{{ old('zip_code') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label for="taxpayer_identification_number" class="form-label">Taxpayer
                                        Identification
                                        Number
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="taxpayer_identification_number"
                                        name="taxpayer_identification_number">
                                        <option value="social_security_number"
                                            {{ old('taxpayer_identification_number') == 'social_security_number' ? 'selected' : '' }}>
                                            Social Security Number</option>
                                        <option value="employer_identification_number"
                                            {{ old('taxpayer_identification_number') == 'employer_identification_number' ? 'selected' : '' }}>
                                            Employer Identification Number</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" id="social_security_number_container">
                                <div class="mb-1">
                                    <label for="social_security_number" class="form-label">
                                        Social Security Number
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="social_security_number"
                                        name="social_security_number" value="{{ old('social_security_number') }}">
                                </div>
                            </div>
                            <div class="col-md-6" id="employer_identification_number_container">
                                <div class="mb-1">
                                    <label for="employer_identification_number" class="form-label">
                                        Employer
                                        Identification
                                        Number
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="employer_identification_number"
                                        name="employer_identification_number"
                                        value="{{ old('employer_identification_number') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label for="bank_account_type" class="form-label">Bank Account Type
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="bank_account_type" name="bank_account_type">
                                        <option value="checking"
                                            {{ old('bank_account_type') == 'checking' ? 'selected' : '' }}>Checking
                                        </option>
                                        <option value="savings"
                                            {{ old('bank_account_type') == 'savings' ? 'selected' : '' }}>Savings
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label for="bank_routing_number" class="form-label">Bank Routing Number
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="bank_routing_number"
                                        name="bank_routing_number" value="{{ old('bank_routing_number') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label for="bank_account_number" class="form-label">Bank Account Number
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="bank_account_number"
                                        name="bank_account_number" value="{{ old('bank_account_number') }}">
                                </div>
                            </div>

                            {{-- message --}}
                            <div class="col-12">
                                <div class="mb-1">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control" rows="4" id="message" name="message">{{ old('message') }}</textarea>
                                </div>
                            </div>
                            <hr>

                            {{-- Password --}}
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label for="password" class="form-label">Password
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>

                            {{-- Confirm Password --}}
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label for="password_confirmation" class="form-label">Confirm Password
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" required>
                                </div>
                            </div>

                            <div class="mt-2 col-12">
                                <!-- Google reCAPTCHA -->
                                <div class="form-group">
                                    <div class="g-recaptcha" data-sitekey="{{ get_recaptcha_site_key() }}"></div>
                                    @if ($errors->has('g-recaptcha-response'))
                                        <p class="text-danger">{{ $errors->first('g-recaptcha-response') }}</p>
                                    @endif
                                </div>
                            </div>



                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif


                    <div class="col-12 d-flex flex-wrap justify-content-between align-items-center" style="gap: 16px; margin-top: 8px; padding-top: 24px; border-top: 1px solid var(--gr-line-soft);">
                        <p class="mb-0" style="font-size: 14px; color: var(--gr-muted);">Already a rep? <a href="{{ route('login') }}" class="gr-link">Sign in</a></p>
                        <button type="submit" class="gr-btn gr-btn-primary">Submit application</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        // toggle social security number and employer identification number fields based on taxpayer identification number
        $(document).ready(function() {
            $('#taxpayer_identification_number').on('change', function() {
                if ($(this).val() === 'social_security_number') {
                    $('#social_security_number_container').show();
                    $('#social_security_number').attr('required', true);
                    $('#employer_identification_number_container').hide();
                    $('#employer_identification_number').removeAttr('required');
                } else {
                    $('#social_security_number_container').hide();
                    $('#social_security_number').removeAttr('required');
                    $('#employer_identification_number_container').show();
                    $('#employer_identification_number').attr('required', true);
                }
            }).trigger('change'); // Trigger change event on page load to set the initial state


            // toggle other info field based on federal tax classification
            $('#faderal_tax_classification').on('change', function() {
                if ($(this).val() === 'other') {
                    $('#other_info_container').show();
                    $('#other_info').attr('required', true);
                } else {
                    $('#other_info_container').hide();
                    $('#other_info').removeAttr('required');
                }
            }).trigger('change'); // Trigger change event on page load to set the initial state
        });
    </script>


    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCAAWB_WeAxjCYM_vtNn30Oqo-SZP3cYDE&libraries=places">
    </script>

    <script>
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
    </script>

    <script>
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
        });
    </script>
@endpush
