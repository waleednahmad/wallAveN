<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Dealer Account Approved - {{ getWebsiteTitle() }}</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; text-align: center;">
    <div style="max-width: 600px; background: #ffffff; padding: 20px; border-radius: 5px; margin: auto;">
        <img src="{{ getMainImage() }}" alt="{{ getWebsiteTitle() }}" style="max-width: 200px;">
        <h2 style="color: #333;">Your Dealer Account is Approved!</h2>
        <p style="font-size: 16px; color: #555;">
            Congratulations! Your dealer application for <strong>{{ getWebsiteTitle() }}</strong> has been approved.
            You can now log in to browse our products and place orders.
        </p>
        <p style="font-size: 16px; color: #555;">
            Click below to log in and start shopping:
        </p>
        <a href="{{ route('login') }}?email={{ $dealer->email }}"
            style="display: inline-block; background: #000000; color: #ffffff; padding: 12px 20px; text-decoration: none; font-size: 16px; border-radius: 5px;">Login
            to Your Account
        </a>
        <p style="font-size: 16px; color: #555;">
            If you have any questions, contact us.
        </p>
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
