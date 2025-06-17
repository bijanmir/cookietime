<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'stripe_session_id',
        'stripe_payment_intent_id',
        'status',
        'total_amount',
        'total_items',
        'customer_email',
        'customer_name',
        'shipping_address',
        'items',
        'paid_at'
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'items' => 'array',
        'paid_at' => 'datetime',
        'total_amount' => 'decimal:2'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber()
    {
        do {
            $orderNumber = 'CT-' . strtoupper(uniqid());
        } while (self::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}
