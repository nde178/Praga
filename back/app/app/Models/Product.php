<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $table = 'products'; // Убедитесь, что это соответствует таблице

    protected $fillable = [
        'description',
        'name',
        'old_price',
        'new_price',
        'main_image',
        'carousel_images',
        'stock_quantity',
        'unique_code',
        'slug',
        'category_id',
    ];

    protected $dates = ['deleted_at']; // Поле для мягкого удаления
    // Связь с категорией

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Связь с тегами (многие ко многим)
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
