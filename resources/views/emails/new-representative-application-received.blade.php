<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>New Representative Rep Application Received - {{ getWebsiteTitle() }}</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #F5EFE6; padding: 20px; text-align: center;">
    <div style="max-width: 600px; background: #FFFCF7; padding: 28px; border-radius: 14px; border: 1px solid #E3D8C8; margin: auto;">
        <img src="{{ getMainImage() }}" alt="{{ getWebsiteTitle() }}" style="max-width: 200px;">
        <h2 style="color: #231A14;">New Representative Rep Application Received</h2>
        <p style="font-size: 16px; color: #5F534A;">
            A new representative rep application has been submitted on the <strong>{{ getWebsiteTitle() }}</strong> website.
        </p>
        <p style="font-size: 16px; color: #5F534A;">
            Please review the application details in the admin panel and take the necessary actions.
        </p>
        <p style="font-size: 16px; color: #5F534A;">
            <strong>Representative Rep Details:</strong><br>
            👤 <strong>Name:</strong> {{ $name }} <br>
            ✉️ <strong>Email:</strong> <a href="mailto:{{ $email }}"
                style="color: #7B2D26; text-decoration: none;">{{ $email }}</a> <br>
            📞 <strong>Phone:</strong> <a href="tel:{{ $phone }}">{{ $phone }}</a>
        </p>
        <br>
        <hr>
        <br>
        <p style="font-size: 16px; color: #5F534A;">
            @include('emails.partials.contact')
        </p>
        <p style="font-size: 16px; color: #5F534A;">Best regards, <br> <strong>{{ getWebsiteTitle() }}</strong></p>
    </div>
</body>

</html>
