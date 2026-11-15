{{-- Company contact block at the bottom of every email (from the store settings) --}}
@if (companyAddress())
    📍 <strong>Address:</strong> {{ companyAddress() }} <br>
@endif
@if (companyPhone())
    📞 <strong>Phone:</strong> <a href="tel:{{ companyTel() }}" style="color: #7B2D26; text-decoration: none;">{{ companyPhone() }}</a> <br>
@endif
@if (companyEmail())
    ✉️ <strong>Email:</strong> <a href="mailto:{{ companyEmail() }}" style="color: #7B2D26; text-decoration: none;">{{ companyEmail() }}</a>
@endif
