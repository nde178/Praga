<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['customer_id', 'company_id', 'invoice_date', 'total_amount', 'status'];
    public static function generateNumber()
    {
        $datePrefix = Carbon::now()->format('Ymd'); // Пример: 20241208
        $lastInvoice = self::whereDate('invoice_date', Carbon::today())
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastInvoice ? (int)substr($lastInvoice->number, -3) + 1 : 1;

        return $datePrefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    // Преобразование полей в объекты Carbon
    protected $casts = [
        'invoice_date' => 'datetime',
    ];
}
