<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbandonedCartEmail extends Model
{
    use HasFactory;

    // Разрешенные для массового заполнения поля
    protected $fillable = [
        'cart_id',
        'email',
        'sent',
    ];

    /**
     * Связь с моделью Cart (один к одному).
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
