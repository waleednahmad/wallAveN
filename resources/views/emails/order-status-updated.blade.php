<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Order Status Update - {{ getWebsiteTitle() }}</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; text-align: center;">
    <div style="max-width: 600px; background: #ffffff; padding: 20px; border-radius: 5px; margin: auto;">
        <img src="{{ getMainImage() }}" alt="{{ getWebsiteTitle() }}" style="max-width: 200px;">
        <h2 style="color: #333;">Your Order Status Has Been Updated</h2>
        <p style="font-size: 16px; color: #555;">
            Hello <strong>{{ $order->dealer->name }}</strong>,
        </p>
        <p style="font-size: 16px; color: #555;">
            The status of your order <strong>#{{ $order->id }}</strong> has been updated to:
        </p>
        <p style="font-size: 18px; color: #d19c4b; font-weight: bold;">
            {{ $order->status }}
        </p>
        <p style="font-size: 16px; color: #555;">
            You can view your order details and track updates from your account.
        </p>
        <a href="{{ route('dealer.orders') }}"
            style="display: inline-block; background: #000000; color: #ffffff; padding: 12px 20px; text-decoration: none; font-size: 16px; border-radius: 5px;">View
            My Order</a>
        <p style="font-size: 16px; color: #555;">
            If you have any questions or need assistance, feel free to contact us.
        </p>
        <br />
        <hr /><br />
        <p style="font-size: 16px; color: #555;">
            📍 <strong>Address:</strong> 4528 W 51st St, Chicago, IL 60632 <br>
            📞 <strong>Phone:</strong> <a href="tel:(773) 490-3801">(773) 490-3801</a> <br>
            ✉️ <strong>Email:</strong> <a href="mailto:sales@goldenrugsinc.com"
                style="color: #d19c4b; text-decoration: none;">sales@goldenrugsinc.com</a>
        </p>
        <p style="font-size: 16px; color: #555;">Best regards, <br>
            <strong>{{ getWebsiteTitle() }}</strong>
        </p>
    </div>
</body>

</html>
