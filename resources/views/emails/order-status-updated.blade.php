<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Order Status Update - {{ getWebsiteTitle() }}</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #F5EFE6; padding: 20px; text-align: center;">
    <div style="max-width: 600px; background: #FFFCF7; padding: 28px; border-radius: 14px; border: 1px solid #E3D8C8; margin: auto;">
        <img src="{{ getMainImage() }}" alt="{{ getWebsiteTitle() }}" style="max-width: 200px;">
        <h2 style="color: #231A14;">Your Order Status Has Been Updated</h2>
        <p style="font-size: 16px; color: #5F534A;">
            Hello <strong>{{ $order->dealer->name }}</strong>,
        </p>
        <p style="font-size: 16px; color: #5F534A;">
            The status of your order <strong>#{{ $order->id }}</strong> has been updated to:
        </p>
        <p style="font-size: 18px; color: #7B2D26; font-weight: bold;">
            {{ ucfirst($order->status) }}
        </p>
        <p style="font-size: 16px; color: #5F534A;">
            You can view your order details and track updates from your account.
        </p>
        <a href="{{ route('dealer.orders') }}"
            style="display: inline-block; background: #7B2D26; color: #ffffff; padding: 12px 20px; text-decoration: none; font-size: 16px; border-radius: 10px;">View
            My Order</a>
        <p style="font-size: 16px; color: #5F534A;">
            If you have any questions or need assistance, feel free to contact us.
        </p>
        <br />
        <hr /><br />
        <p style="font-size: 16px; color: #5F534A;">
            @include('emails.partials.contact')
        </p>
        <p style="font-size: 16px; color: #5F534A;">Best regards, <br>
            <strong>{{ getWebsiteTitle() }}</strong>
        </p>
    </div>
</body>

</html>
