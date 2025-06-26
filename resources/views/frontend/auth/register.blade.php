@extends('frontend.layout.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <h2 class="mb-4 text-center">BECOME A DEALER</h2>

                <div class="card">
                    <div class="card-body">
                        <form class="row g-4" action="{{ route('frontend.submitRegister') }}" method="POST" id="demo-form"
                            enctype="multipart/form-data">
                            @csrf
                            <!-- Left Column -->
                            <div class="col-md-6 ">
                                <label for="company_name" class="form-label">Company Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="company_name" name="company_name"
                                    value="{{ old('company_name') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="tax_id" class="form-label">Tax ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="tax_id" name="tax_id"
                                    value="{{ old('tax_id') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="address" class="form-label">Address<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="address" name="address"
                                    value="{{ old('address') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="city" class="form-label">City<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="city" name="city"
                                    value="{{ old('city') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="state" class="form-label">State<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="state" name="state"
                                    value="{{ old('state') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="zip_code" class="form-label">Zip Code
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" required class="form-control" id="zip_code"
                                    value="{{ old('zip_code') }}" name="zip_code">
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <label for="years_in_business" class="form-label">Years in Business<span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="years_in_business"
                                    value="{{ old('years_in_business') }}" name="years_in_business" required>
                            </div>

                            <div class="col-md-6">
                                <label for="website" class="form-label">Website</label>
                                <input type="text" class="form-control" id="website" value="{{ old('website') }}"
                                    name="website">
                            </div>

                            <div class="col-md-6">
                                <label for="business_type" class="form-label">Business Type<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="{{ old('business_type') }}"
                                    id="business_type" name="business_type" required>
                            </div>

                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required
                                    value="{{ old('name') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number<span
                                        class="text-danger">*</span></label>
                                <input type="tel" class="form-control" value="{{ old('phone') }}" id="phone"
                                    name="phone" required>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                                <input type="email" class="form-control" value="{{ old('email') }}" id="email"
                                    name="email" required>
                            </div>

                            {{-- resale_certificate --}}
                            <div class="col-md-6">
                                <label for="resale_certificate" class="form-label">Resale Certificate</label>
                                <input type="file" class="form-control" id="resale_certificate"
                                    accept="application/pdf , image/*" name="resale_certificate">
                            </div>


                            <input type="hidden" class="form-control" id="ref" name="ref"
                                value="{{ request()->get('ref') }}">


                            <!-- Full Width Fields -->
                            <div class="col-12">
                                <div class="col-12">
                                    <label for="message" class="form-label">
                                        Message
                                    </label>
                                    <textarea class="form-control" rows="4" id="message" name="message">{{ old('message') }}</textarea>
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
                            </div>
                            {{-- password and password confirmations --}}
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password<span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm Password<span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" required>
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


                            <button type="submit" class="btn btn-secondary mt-3 "
                                style="max-width: 200px; margin: 0 auto; display: block;">Submit</button>

                            <p class="mt-3 text-center">
                                Already have an account?
                                <a href="{{ route('login') }}" class="text-underlined">
                                    <b>
                                        Login
                                    </b>
                                </a>
                            </p>

                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

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
