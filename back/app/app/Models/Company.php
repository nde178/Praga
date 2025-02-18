<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['customer_id', 'company_name', 'ico_number', 'dic_number', 'company_address', 'phone', 'email'];

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
