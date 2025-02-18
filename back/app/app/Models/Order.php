<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'customer_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'delivery_method',
        'delivery_price',
        'payment_method',
        'payment_price',
        'total_price',
        'message',
        'newsletterAccepted',
        'status',
    ];

    // Связь с корзиной

    public function cart(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    // Связь с пользователем
    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}

