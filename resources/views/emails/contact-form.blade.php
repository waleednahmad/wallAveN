<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Contact Us - {{ getWebsiteTitle() }}</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #F5EFE6; padding: 20px; text-align: center;">
    <div style="max-width: 600px; background: #FFFCF7; padding: 28px; border-radius: 14px; border: 1px solid #E3D8C8; margin: auto;">
        <img src="{{ getMainImage() }}" alt="{{ getWebsiteTitle() }}" style="max-width: 200px;">
        <h2 style="color: #231A14;">New Contact Us Message</h2>
        <p style="font-size: 16px; color: #5F534A;">
            You have received a new message from the contact form on <strong>{{ getWebsiteTitle() }}</strong>.
        </p>
        <table style="width: 100%; margin: 20px 0; font-size: 16px; color: #5F534A; text-align: left;">
            <tr>
                <td style="padding: 8px 0; width: 120px;"><strong>Name:</strong></td>
                <td>{{ $name }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0;"><strong>Email:</strong></td>
                <td>{{ $email }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0;"><strong>Phone:</strong></td>
                <td>{{ $phone }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0;"><strong>Subject:</strong></td>
                <td>{{ $emailSubject }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; vertical-align: top;"><strong>Message:</strong></td>
                <td>{{ $emailMessage }}</td>
            </tr>
        </table>
        <hr>
        <p style="font-size: 16px; color: #5F534A;">
            @include('emails.partials.contact')
        </p>
        <p style="font-size: 16px; color: #5F534A;">Best regards, <br> <strong>{{ getWebsiteTitle() }}</strong></p>
    </div>
</body>

</html>
