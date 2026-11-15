<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Dealer Application Received - {{ getWebsiteTitle() }}</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #F5EFE6; padding: 20px; text-align: center;">
    <div style="max-width: 600px; background: #FFFCF7; padding: 28px; border-radius: 14px; border: 1px solid #E3D8C8; margin: auto;">
        <img src="{{ getMainImage() }}" alt="{{ getWebsiteTitle() }}" style="max-width: 200px;">
        <h2 style="color: #231A14;">Thank You for Registering!</h2>
        <p style="font-size: 16px; color: #5F534A;">
            We have received your dealer application at <strong>{{ getWebsiteTitle() }}</strong>
            Our team will review your information and get back to you shortly.
        </p>
        <p style="font-size: 16px; color: #5F534A;">
            If you have any questions, feel free to contact us.
        </p>
        <p style="font-size: 16px; color: #5F534A;">
            @include('emails.partials.contact')
        </p>
        <p style="font-size: 16px; color: #5F534A;">Best regards, <br> <strong>{{ getWebsiteTitle() }}</strong></p>
    </div>
</body>

</html>
