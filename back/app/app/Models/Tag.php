<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = ['order', 'name', 'slug'];  // Массово заполняемые поля
    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($tag) {
            $tag->slug = Str::slug($tag->name);
        });

        static::updating(function ($tag) {
            $tag->slug = Str::slug($tag->name);
        });
    }
    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class);
    }
}
