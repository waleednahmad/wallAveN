<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Dealer Account Approved - {{ getWebsiteTitle() }}</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #F5EFE6; padding: 20px; text-align: center;">
    <div style="max-width: 600px; background: #FFFCF7; padding: 28px; border-radius: 14px; border: 1px solid #E3D8C8; margin: auto;">
        <img src="{{ getMainImage() }}" alt="{{ getWebsiteTitle() }}" style="max-width: 200px;">
        <h2 style="color: #231A14;">Your Dealer Account is Approved!</h2>
        <p style="font-size: 16px; color: #5F534A;">
            Congratulations! Your dealer application for <strong>{{ getWebsiteTitle() }}</strong> has been approved.
            You can now log in to browse our products and place orders.
        </p>
        <p style="font-size: 16px; color: #5F534A;">
            Click below to log in and start shopping:
        </p>
        <a href="{{ route('login') }}?email={{ $dealer->email }}"
            style="display: inline-block; background: #7B2D26; color: #ffffff; padding: 12px 20px; text-decoration: none; font-size: 16px; border-radius: 10px;">Login
            to Your Account
        </a>
        <p style="font-size: 16px; color: #5F534A;">
            If you have any questions, contact us.
        </p>
        <hr>
        <p style="font-size: 16px; color: #5F534A;">
            @include('emails.partials.contact')
        </p>
        <p style="font-size: 16px; color: #5F534A;">Best regards, <br> <strong>{{ getWebsiteTitle() }}</strong></p>
    </div>
</body>

</html>
