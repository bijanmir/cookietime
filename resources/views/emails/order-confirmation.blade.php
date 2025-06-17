{{-- resources/views/emails/order-confirmation.blade.php --}}
    <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Confirmation - {{ $order->order_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f59e0b; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: white; padding: 20px; border: 1px solid #e5e7eb; }
        .order-item { border-bottom: 1px solid #e5e7eb; padding: 15px 0; }
        .order-item:last-child { border-bottom: none; }
        .total { background: #f9fafb; padding: 15px; border-radius: 6px; margin-top: 20px; }
        .button { display: inline-block; background: #f59e0b; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 20px 0; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🍪 Order Confirmation</h1>
        <p>Thank you for your order, {{ $order->customer_name }}!</p>
    </div>

    <div class="content">
        <h2>Order Details</h2>
        <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
        <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
        <p><strong>Email:</strong> {{ $order->customer_email }}</p>

        <h3>Your Cookie Box</h3>
        @foreach($order->items as $item)
            <div class="order-item">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong>{{ $item['name'] }}</strong><br>
                        <small>{{ $item['description'] }}</small><br>
                        <span style="color: #f59e0b;">${{ number_format($item['price'], 2) }} each</span>
                    </div>
                    <div style="text-align: right;">
                        <div>{{ $item['quantity'] }} × ${{ number_format($item['price'], 2) }}</div>
                        <strong>${{ number_format($item['subtotal'], 2) }}</strong>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="total">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <strong>Total ({{ $order->total_items }} cookies)</strong>
                </div>
                <div>
                    <strong style="font-size: 1.2em;">${{ number_format($order->total_amount, 2) }}</strong>
                </div>
            </div>
        </div>

        @if($order->shipping_address)
            <h3>Shipping Address</h3>
            <p>
                {{ $order->customer_name }}<br>
                {{ $order->shipping_address['line1'] ?? '' }}<br>
                @if(isset($order->shipping_address['line2']) && $order->shipping_address['line2'])
                    {{ $order->shipping_address['line2'] }}<br>
                @endif
                {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}<br>
                {{ $order->shipping_address['country'] ?? '' }}
            </p>
        @endif

        <h3>What's Next?</h3>
        <ul>
            <li>We'll start baking your fresh cookies right away</li>
            <li>Your order will ship within 1-2 business days</li>
            <li>Estimated delivery: 3-5 business days</li>
            <li>You'll receive a tracking number once your order ships</li>
        </ul>

        <div style="text-align: center;">
            <a href="{{ config('app.url') }}" class="button">Order More Cookies</a>
        </div>

        <p><small>If you have any questions about your order, please reply to this email or contact us at orders@cookietime.com</small></p>
    </div>
</div>
</body>
</html>
