<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_users', 'user_id', 'role_id');
    }
    public function hasAllPermissions()
    {
        $permissions = json_decode($this->permissions, true);
        return isset($permissions['all']) && $permissions['all'] === true;
    }

    // Проверка, есть ли у пользователя определённая роль
    public function hasRole($roleSlug)
    {
        return $this->roles->contains('slug', $roleSlug);
    }

    /**
     * Проверяет, является ли пользователь администратором.
     *
     * @return bool
     */
    public function isAdmin()
    {
        // Предположим, что роль администратора имеет slug 'admin'
        return $this->roles()->where('slug', 'admin')->exists();
    }
}
