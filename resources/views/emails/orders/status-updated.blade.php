<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update</title>
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
        .status-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .status {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
            margin: 10px;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-processing { background: #dbeafe; color: #1e40af; }
        .status-shipped { background: #d1fae5; color: #065f46; }
        .status-delivered { background: #dcfce7; color: #166534; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
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
        <p style="margin: 10px 0 0 0;">Order Status Update</p>
    </div>

    <div class="content">
        <h2>Hi {{ $order->user->name }},</h2>
        <p>Your order status has been updated!</p>

        <div class="status-box">
            <p><strong>Order #{{ $order->id }}</strong></p>
            <div>
                <span class="status status-{{ $oldStatus }}">{{ ucfirst($oldStatus) }}</span>
                →
                <span class="status status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
            </div>
        </div>

        @if($order->status === 'shipped')
            <p style="background: #dcfce7; padding: 15px; border-radius: 6px; border-left: 4px solid #16a34a;">
                <strong>🎉 Great news!</strong> Your order has been shipped and is on its way to you!
            </p>
        @elseif($order->status === 'delivered')
            <p style="background: #dcfce7; padding: 15px; border-radius: 6px; border-left: 4px solid #16a34a;">
                <strong>✅ Delivered!</strong> Your order has been delivered. We hope you enjoy your purchase!
            </p>
        @elseif($order->status === 'cancelled')
            <p style="background: #fee2e2; padding: 15px; border-radius: 6px; border-left: 4px solid #dc2626;">
                <strong>Order Cancelled</strong> - Your order has been cancelled. If you have any questions, please contact our support team.
            </p>
        @endif

        <p><strong>Order Details:</strong><br>
        Total: £{{ number_format($order->total_amount, 2) }}<br>
        Order Date: {{ $order->created_at->format('F j, Y') }}</p>

        <center>
            <a href="{{ url('/') }}" class="button">View Order Details</a>
        </center>
    </div>

    <div class="footer">
        <p>TechVerse - Your Universe of Technology</p>
        <p>This is an automated email. Please do not reply.</p>
    </div>
</body>
</html>
