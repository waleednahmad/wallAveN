<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>New Dealer Application Received - {{ getWebsiteTitle() }}</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; text-align: center;">
    <div style="max-width: 600px; background: #ffffff; padding: 20px; border-radius: 5px; margin: auto;">
        <img src="{{ getMainImage() }}" alt="{{ getWebsiteTitle() }}" style="max-width: 200px;">
        <h2 style="color: #333;">New Dealer Application Received</h2>
        <p style="font-size: 16px; color: #555;">
            A new dealer application has been submitted on the <strong>{{ getWebsiteTitle() }}</strong> website.
        </p>
        <p style="font-size: 16px; color: #555;">
            Please review the application details in the admin panel and take the necessary actions.
        </p>
        <p style="font-size: 16px; color: #555;">
            <strong>Dealer Details:</strong><br>
            👤 <strong>Name:</strong> {{ $name }} <br>
            ✉️ <strong>Email:</strong> <a href="mailto:{{ $email }}"
                style="color: #d19c4b; text-decoration: none;">{{ $email }}</a> <br>
            📞 <strong>Phone:</strong> <a href="tel:{{ $phone }}">{{ $phone }}</a> <br>
            🏠 <strong>Address:</strong>
            {{ $address }}<br>
            {{ $city }}{{ $city != '---' ? ',' : '' }}
            {{ $state }}{{ $state != '---' ? ' ' : '' }}
            {{ $zip_code }}
        </p>

        <a href="{{ route('dashboard.dealers.index') }}?email={{ $dealer->email }}"
            style="display: inline-block; background: #000000; color: #ffffff; padding: 12px 20px; text-decoration: none; font-size: 16px; border-radius: 5px;">
            Review Application
        </a>
        <br>
        <hr>
        <br>
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
