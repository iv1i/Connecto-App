<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'name',
        'link_name',
        'email',
        'password',
        'role',
        'is_blocked',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_blocked' => 'boolean',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function chatRooms()
    {
        return $this->belongsToMany(ChatRoom::class)
            ->withPivot('joined_via', 'joined_at')
            ->withTimestamps();
    }

    public function isBlocked(): bool
    {
        return $this->is_blocked;
    }
}
