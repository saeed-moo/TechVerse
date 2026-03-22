<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #9333ea 0%, #7c3aed 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .order-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .order-item {
            border-bottom: 1px solid #e5e7eb;
            padding: 15px 0;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            color: #9333ea;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #9333ea;
        }
        .button {
            display: inline-block;
            background: #9333ea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">TechVerse</h1>
        <p style="margin: 10px 0 0 0;">Thank you for your order!</p>
    </div>

    <div class="content">
        <h2>Hi {{ $order->user->name }},</h2>
        <p>We've received your order and it's being processed. Here are your order details:</p>

        <div class="order-details">
            <p><strong>Order Number:</strong> #{{ $order->id }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y g:i A') }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>

            <h3>Order Items:</h3>
            @foreach($order->items as $item)
                <div class="order-item">
                    <strong>{{ $item->product->name }}</strong><br>
                    Quantity: {{ $item->quantity }} × £{{ number_format($item->price, 2) }}
                    = £{{ number_format($item->quantity * $item->price, 2) }}
                </div>
            @endforeach

            <div class="total">
                Total: £{{ number_format($order->total_amount, 2) }}
            </div>
        </div>

        <p><strong>Shipping Address:</strong><br>
        {{ $order->shipping_address }}<br>
        {{ $order->shipping_city }}, {{ $order->shipping_postcode }}</p>

        <p>We'll send you another email when your order ships.</p>

        <center>
            <a href="{{ url('/') }}" class="button">Visit Our Store</a>
        </center>
    </div>

    <div class="footer">
        <p>TechVerse - Your Universe of Technology</p>
        <p>This is an automated email. Please do not reply.</p>
    </div>
</body>
</html>
