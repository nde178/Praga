<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;


// Добавляем трейт для работы с токенами

class Customer extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'phone'];
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function cart(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Cart::class, 'customer_id');
    }

    public function tokens(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany('App\Models\PersonalAccessToken', 'tokenable');
    }
    public function addresses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Address::class, 'customer_id', 'id');
    }
    public function altaddresses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Altaddress::class, 'customer_id', 'id');
    }

    public function company(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Company::class, 'customer_id', 'id');
    }

    public function invoices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invoice::class, 'customer_id', 'id');
    }
}
