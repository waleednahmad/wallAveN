<div class="gr-contact">
    <div class="gr-container" style="padding-top: 64px; padding-bottom: 96px;">
        <div class="gr-rise">
            <p class="gr-eyebrow no-rule">Contact us</p>
            <h1 class="gr-display" style="font-size: clamp(38px, 5vw, 64px); max-width: 820px; margin-bottom: 40px;">Talk to a person, <em>seven days a week.</em></h1>
        </div>

        <div class="row g-4" style="margin-bottom: 32px;">
            <div class="col-md-4">
                <a href="tel:+17734903801" class="d-block h-100 text-decoration-none" style="background: var(--gr-madder); color: #fff; border-radius: 18px; padding: 28px;">
                    <i class="bi bi-telephone" aria-hidden="true" style="font-size: 26px; color: var(--gr-saffron);"></i>
                    <p style="margin: 18px 0 4px; font-size: 14px; color: #F1DCD5;">Call us</p>
                    <p style="margin: 0; font-family: var(--gr-font-display); font-size: 30px; color: #fff;">(773) 490-3801</p>
                    <p style="margin: 8px 0 0; font-size: 14px; color: #F1DCD5;">The fastest way to reach us</p>
                </a>
            </div>
            <div class="col-md-4">
                <div class="gr-card h-100" style="padding: 28px;">
                    <i class="bi bi-clock" aria-hidden="true" style="font-size: 26px; color: var(--gr-madder);"></i>
                    <p style="margin: 18px 0 8px; font-size: 14px; color: var(--gr-muted);">Opening hours</p>
                    <p style="margin: 0 0 4px; display: flex; justify-content: space-between;"><span>Mon – Sat</span><strong style="font-weight: 600;">9:00 am – 7:30 pm</strong></p>
                    <p style="margin: 0; display: flex; justify-content: space-between;"><span>Sunday</span><strong style="font-weight: 600;">9:30 am – 5:30 pm</strong></p>
                </div>
            </div>
            <div class="col-md-4">
                <a href="https://www.google.com/maps/search/?api=1&query=Golden+Rugs+Wholesale+Chicago" target="_blank" rel="noopener" class="gr-card d-block h-100 text-decoration-none" style="padding: 28px; color: var(--gr-ink);">
                    <i class="bi bi-geo-alt" aria-hidden="true" style="font-size: 26px; color: var(--gr-madder);"></i>
                    <p style="margin: 18px 0 4px; font-size: 14px; color: var(--gr-muted);">Visit the warehouse</p>
                    <p style="margin: 0; font-family: var(--gr-font-display); font-size: 26px;">Chicago, IL</p>
                    <p style="margin: 8px 0 0; font-size: 14px; color: var(--gr-madder); font-weight: 600;">Get directions →</p>
                </a>
            </div>
        </div>

        <div class="d-flex flex-wrap align-items-start" style="gap: 32px;">
            <div class="gr-card" style="flex: 3 1 520px; min-width: 0; padding: clamp(24px, 4vw, 40px);">
                <h2 style="font-size: 34px; margin: 0 0 6px;">Send us a message</h2>
                <p style="color: var(--gr-muted); margin: 0 0 28px;">We'll get back to you as soon as possible.</p>
                <form wire:submit.prevent='sendEmail' class="row g-3">
                    <div class="col-md-6">
                        <label for="c_name" class="form-label">Full name <span class="gr-required">*</span></label>
                        <input type="text" class="form-control" id="c_name" wire:model='name' autocomplete="name">
                        @error('name') <span class="text-danger" style="font-size: 14px;">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone <span class="gr-required">*</span></label>
                        <input type="text" class="form-control" id="phone" wire:model='phone' autocomplete="tel">
                        @error('phone') <span class="text-danger" style="font-size: 14px;">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-12">
                        <label for="c_email" class="form-label">Email <span class="gr-required">*</span></label>
                        <input type="email" class="form-control" id="c_email" wire:model='email' autocomplete="email">
                        @error('email') <span class="text-danger" style="font-size: 14px;">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-12">
                        <label for="c_subject" class="form-label">Subject <span class="gr-required">*</span></label>
                        <input type="text" class="form-control" id="c_subject" wire:model='emailSubject' list="gr-subjects" placeholder="e.g. Becoming a dealer">
                        <datalist id="gr-subjects">
                            <option value="Becoming a dealer"></option>
                            <option value="An existing order"></option>
                            <option value="Product question"></option>
                        </datalist>
                        @error('emailSubject') <span class="text-danger" style="font-size: 14px;">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-12">
                        <label for="c_message" class="form-label">Message <span class="gr-required">*</span></label>
                        <textarea class="form-control" id="c_message" rows="5" wire:model='emailMessage' placeholder="How can we help?"></textarea>
                        @error('emailMessage') <span class="text-danger" style="font-size: 14px;">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="gr-btn gr-btn-dark" wire:loading.attr="disabled">
                            <span wire:loading wire:target="sendEmail"><i class="fas fa-spinner fa-spin" aria-hidden="true"></i></span>
                            Send message
                        </button>
                    </div>
                </form>
            </div>
            <div style="flex: 2 1 360px; min-width: 0;">
                <div style="border-radius: 20px; overflow: hidden; border: 1px solid var(--gr-line); height: 420px; background: #E9DFD0;">
                    <iframe title="Golden Rugs Wholesale on Google Maps"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3710.085197220118!2d-87.7387865232435!3d41.80106127125036!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x880e31fb5a7f6027%3A0xb715349c5ce00ca4!2sGolden%20Rugs%20Wholesale!5e1!3m2!1sen!2sus!4v1742868861586!5m2!1sen!2sus"
                        width="100%" height="100%" style="border:0; display: block;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
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
            grToast('success', event);
            // make the submit button disable  for 1.4 seconds then redirect to the home page
            setTimeout(() => {
                window.location.href = "{{ route('frontend.home') }}";
            }, 1400);
        });
    </script>
@endscript
