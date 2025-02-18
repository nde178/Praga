<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['product_id', 'author', 'content', 'rating'];

    // Связь с продуктом
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
