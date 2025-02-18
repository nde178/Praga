<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonalAccessToken extends Model
{
    use HasFactory;

    // Укажите имя таблицы, если оно отличается от имени модели
    protected $table = 'personal_access_tokens';

    // Укажите, какие поля могут быть массово назначаемыми
    protected $fillable = [
        'tokenable_type',
        'tokenable_id',
        'name',
        'token',
        'abilities',
        'last_used_at',
        'expires_at',
    ];

    // Определите полиморфную связь
    public function tokenable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
