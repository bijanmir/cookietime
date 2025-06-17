<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Confirmation - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
        }
        .header {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        .content {
            padding: 30px 20px;
        }
        .order-item {
            border-bottom: 1px solid #e5e7eb;
            padding: 20px 0;
            display: flex;
            align-items: center;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .item-image {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #fef3c7, #fed7aa);
            border-radius: 8px;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }
        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }
        .item-details {
            flex: 1;
        }
        .item-name {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }
        .item-description {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 5px;
            line-height: 1.4;
        }
        .item-price {
            color: #f59e0b;
            font-weight: bold;
            font-size: 14px;
        }
        .item-quantity {
            text-align: right;
            min-width: 80px;
        }
        .total {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
            border: 1px solid #e5e7eb;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .button {
            display: inline-block;
            background: #f59e0b;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            margin: 20px 0;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .button:hover {
            background: #d97706;
        }
        .next-steps {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .next-steps ul {
            margin: 10px 0;
            padding-left: 0;
            list-style: none;
        }
        .next-steps li {
            padding: 5px 0;
            display: flex;
            align-items: center;
        }
        .next-steps li::before {
            content: "✓";
            background: #10b981;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-size: 12px;
            font-weight: bold;
        }
        .address-section {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .footer-note {
            font-size: 12px;
            color: #6b7280;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        /* Mobile responsive */
        @media (max-width: 600px) {
            .order-item {
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
            }
            .item-image {
                margin-bottom: 10px;
                margin-right: 0;
            }
            .item-quantity {
                text-align: left;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🍪 Order Confirmation</h1>
        <p style="margin: 0; font-size: 18px;">Thank you for your order, {{ $order->customer_name }}!</p>
    </div>

    <div class="content">
        <h2 style="color: #374151; margin-bottom: 20px;">Order Details</h2>
        <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
        <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
        <p><strong>Email:</strong> {{ $order->customer_email }}</p>

        <h3 style="color: #374151; margin: 30px 0 20px 0;">Your Cookie Kits</h3>

        @foreach($order->items as $item)
            <div class="order-item">
                <div class="item-image">
                    @if(isset($item['image']) && !empty($item['image']))
                        <img src="{{ config('app.url') }}{{ $item['image'] }}"
                             alt="{{ $item['name'] }}"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div style="font-size: 24px; display: none;">🍪</div>
                    @else
                        <div style="font-size: 24px;">🍪</div>
                    @endif
                </div>
                <div class="item-details">
                    <div class="item-name">{{ $item['name'] }}</div>
                    <div class="item-description">{{ $item['description'] }}</div>
                    <div class="item-price">${{ number_format($item['price'], 2) }} each</div>
                </div>
                <div class="item-quantity">
                    <div style="font-weight: bold;">{{ $item['quantity'] }} kit{{ $item['quantity'] > 1 ? 's' : '' }}</div>
                    <div style="font-size: 12px; color: #6b7280;">{{ $item['quantity'] }} × ${{ number_format($item['price'], 2) }}</div>
                    <div style="font-size: 18px; font-weight: bold; color: #f59e0b; margin-top: 5px;">${{ number_format($item['subtotal'], 2) }}</div>
                </div>
            </div>
        @endforeach

        <div class="total">
            <div class="total-row">
                <div>
                    <strong style="font-size: 18px;">Total ({{ $order->total_items }} cookie kit{{ $order->total_items > 1 ? 's' : '' }})</strong>
                </div>
                <div>
                    <strong style="font-size: 24px; color: #f59e0b;">${{ number_format($order->total_amount, 2) }}</strong>
                </div>
            </div>
        </div>

        @if($order->shipping_address)
            <div class="address-section">
                <h3 style="color: #374151; margin-top: 0;">Shipping Address</h3>
                <p style="margin: 5px 0;">
                    {{ $order->customer_name }}<br>
                    {{ $order->shipping_address['line1'] ?? '' }}<br>
                    @if(isset($order->shipping_address['line2']) && $order->shipping_address['line2'])
                        {{ $order->shipping_address['line2'] }}<br>
                    @endif
                    {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}<br>
                    {{ $order->shipping_address['country'] ?? '' }}
                </p>
            </div>
        @endif

        <div class="next-steps">
            <h3 style="color: #1e40af; margin-top: 0;">What's Next?</h3>
            <ul>
                <li>We'll start preparing your fresh cookie kits right away</li>
                <li>Your order will ship within 1-2 business days</li>
                <li>Estimated delivery: 3-5 business days</li>
                <li>You'll receive a tracking number once your order ships</li>
            </ul>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ config('app.url') }}" class="button">Order More Cookie Kits</a>
        </div>

        <div class="footer-note">
            <p><strong>Questions about your order?</strong></p>
            <p>Reply to this email or contact us at <a href="mailto:orders@cookietime.com" style="color: #f59e0b;">orders@cookietime.com</a></p>
            <p style="margin-top: 20px;">Thank you for choosing CookieTime! We hope you enjoy your delicious cookie kits! 🍪</p>
        </div>
    </div>
</div>
</body>
</html>
