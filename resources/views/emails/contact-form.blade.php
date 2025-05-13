<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Contact Us - {{ getWebsiteTitle() }}</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; text-align: center;">
    <div style="max-width: 600px; background: #ffffff; padding: 20px; border-radius: 5px; margin: auto;">
        <img src="{{ getMainImage() }}" alt="{{ getWebsiteTitle() }}" style="max-width: 200px;">
        <h2 style="color: #333;">New Contact Us Message</h2>
        <p style="font-size: 16px; color: #555;">
            You have received a new message from the contact form on <strong>{{ getWebsiteTitle() }}</strong>.
        </p>
        <table style="width: 100%; margin: 20px 0; font-size: 16px; color: #555; text-align: left;">
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
                <td>{{ $message }}</td>
            </tr>
        </table>
        <hr>
        <p style="font-size: 16px; color: #555;">
            📍 <strong>Address:</strong> 4528 W 51st St, Chicago, IL 60632 <br>
            📞 <strong>Phone:</strong> <a href="tel:(773) 490-3801">(773) 490-3801</a> <br>
            ✉️ <strong>Email:</strong> <a href="mailto:sales@goldenrugsinc.com"
                style="color: #d19c4b; text-decoration: none;">sales@goldenrugsinc.com</a>
        </p>
        <p style="font-size: 16px; color: #555;">Best regards, <br> <strong>{{ getWebsiteTitle() }}</strong></p>
    </div>
</body>

</html>
