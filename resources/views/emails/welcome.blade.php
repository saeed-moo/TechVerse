<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to TechVerse</title>
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
            padding: 40px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .feature-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
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
        <h1 style="margin: 0; font-size: 36px;">Welcome to TechVerse! 🎉</h1>
        <p style="margin: 10px 0 0 0;">Your Universe of Technology</p>
    </div>

    <div class="content">
        <h2>Hi {{ $user->name }},</h2>
        <p>Thank you for joining TechVerse! We're excited to have you as part of our community.</p>

        <div class="feature-box">
            <h3>🛍️ What you can do:</h3>
            <ul>
                <li>Browse our wide selection of tech products</li>
                <li>Add items to your wishlist ❤️</li>
                <li>Write reviews and ratings ⭐</li>
                <li>Track your orders</li>
                <li>Get exclusive deals and updates</li>
            </ul>
        </div>

        <p>Start exploring our latest products and find the perfect tech for your needs!</p>

        <center>
            <a href="{{ url('/products') }}" class="button">Browse Products</a>
        </center>

        <p style="margin-top: 30px; color: #6b7280; font-size: 14px;">
            If you have any questions, feel free to reach out to our support team anytime.
        </p>
    </div>

    <div class="footer">
        <p>TechVerse - Your Universe of Technology</p>
        <p>Aston University CS2TP Project 2025-26</p>
    </div>
</body>
</html>
