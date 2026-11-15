<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Dealer application update - {{ getWebsiteTitle() }}</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; text-align: center;">
    <div style="max-width: 600px; background: #ffffff; padding: 20px; border-radius: 5px; margin: auto;">
        <img src="{{ getMainImage() }}" alt="{{ getWebsiteTitle() }}" style="max-width: 200px;">
        <h2 style="color: #333;">About your dealer application</h2>
        <p style="font-size: 16px; color: #555;">
            Thank you for applying for a dealer account with <strong>{{ getWebsiteTitle() }}</strong>.
            After reviewing your application, we are not able to approve it at this time.
        </p>
        @if ($dealer->rejection_reason)
            <p style="font-size: 16px; color: #333; background: #f7f3ec; padding: 12px 16px; border-radius: 5px; text-align: left;">
                <strong>Reason:</strong> {{ $dealer->rejection_reason }}
            </p>
        @endif
        <p style="font-size: 16px; color: #555;">
            If anything has changed, or you think we got something wrong, reply to this email or
            <a href="{{ route('frontend.contactUs') }}" style="color: #7B2D26;">contact us</a> and we will take another look.
        </p>
        <p style="font-size: 16px; color: #555;">Best regards, <br> <strong>{{ getWebsiteTitle() }}</strong></p>
    </div>
</body>

</html>
