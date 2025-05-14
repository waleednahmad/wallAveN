<div class="contact-page mb-120">
    <div class="container">
        <div class="row gy-5">
            <div class="col-lg-5">
                <div class="map-area">
                    <h3>Contact Us by Phone</h3>
                    <div class="single-content">
                        <ul>
                            <li><a href="tel:(773) 490-3801">(773) 490-3801</a></li>
                        </ul>
                        <ul class="opening-time">
                            <li>Mon - Sat <span>9:00 am - 7:30 pm</span></li>
                            <li>Sun <span>9:30 am - 5:30 pm</span></li>
                        </ul>
                    </div>
                    <div class="contact-map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3710.085197220118!2d-87.7387865232435!3d41.80106127125036!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x880e31fb5a7f6027%3A0xb715349c5ce00ca4!2sGolden%20Rugs%20Wholesale!5e1!3m2!1sen!2sus!4v1742868861586!5m2!1sen!2sus"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="enquery-section style-2 ">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 mb-25">
                                <div class="enquery-section-title">
                                    <h3>Contact Us by Email</h3>
                                    <p>Have questions or need assistance? We're here to help! Reach out to us, and
                                        our
                                        team will get back to you as soon as possible.</p>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-20">
                                <div class="enquery-form-wrapper">
                                    <form wire:submit.prevent='sendEmail' class="enquery-form">
                                        <div class="row">
                                            {{-- name --}}
                                            <div class="col-md-6 mb-30">
                                                <div class="form-inner3">
                                                    <label>full name *</label>
                                                    <input type="text" wire:model='name' placeholder="Mr. Harry">
                                                    @error('name')
                                                        <span class="text-danger error-handler">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            {{-- email --}}
                                            <div class="col-md-6 mb-30">
                                                <div class="form-inner3">
                                                    <label>email address *</label>
                                                    <input type="email" wire:model='email'
                                                        placeholder="info@example.com">
                                                    @error('email')
                                                        <span class="text-danger error-handler">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            {{-- phone --}}
                                            <div class="col-md-6 mb-30">
                                                <div class="form-inner3">
                                                    <label>phone number *</label>
                                                    <input type="text" wire:model='phone' id="phone"
                                                        placeholder="(773) 490-3801">
                                                    @error('phone')
                                                        <span class="text-danger error-handler">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            {{-- subject --}}
                                            <div class="col-md-6 mb-30">
                                                <div class="form-inner3">
                                                    <label>subject *</label>
                                                    <input type="text" wire:model='emailSubject'
                                                        placeholder="Subject">
                                                    @error('emailSubject')
                                                        <span class="text-danger error-handler">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            {{-- message --}}
                                            <div class="col-md-12 mb-40">
                                                <div class="form-inner3">
                                                    <label>message *</label>
                                                    <textarea wire:model='emailMessage' placeholder="Write your message"></textarea>
                                                    @error('emailMessage')
                                                        <span class="text-danger error-handler">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12 ">
                                                <div class="form-inner3">
                                                    <button class="custom-black-btn" wire:loading.attr="disabled"
                                                        style="font-size: 16px; padding: 10px 20px;">
                                                        <span wire:loading>
                                                            <i class="fas fa-spinner fa-spin"></i>
                                                        </span>

                                                        <span wire:loading.remove>
                                                            Send 
                                                            <i class="fas fa-paper-plane"></i>
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@script
    <script>
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

        $wire.on('emailSent', (event) => {
            Swal.fire({
                icon: 'success',
                title: event[0],
                timer: 6000,
                showConfirmButton: false,
                toast: true,
                position: 'top-right'
            });
            // make the submit button disable  for 1.4 seconds then redirect to the home page
            setTimeout(() => {
                window.location.href = "{{ route('frontend.home') }}";
            }, 1400);
        });
    </script>
@endscript
