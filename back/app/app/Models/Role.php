<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['slug', 'name', 'permissions'];

    /**
     * Связь "многие ко многим" с таблицей пользователей.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_users', 'role_id', 'user_id');
    }
}
