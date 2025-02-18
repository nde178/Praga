<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /**
     * @var \Illuminate\Support\HigherOrderCollectionProxy|mixed
     */
    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CartItem::class);
    }

}
