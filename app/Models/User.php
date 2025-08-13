<?php

namespace App\Models;

use App\Http\Controllers\API\ChatRoomController;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'name_color'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'role',
        'email'
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
    public function friends(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id');
    }

    // В модели User
    public function allFriends()
    {
        // Получаем друзей, где текущий пользователь инициатор
        $initiatedFriends = $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
            ->withPivot('status', 'created_at', 'updated_at') // Загружаем данные из таблицы friendships
            ->get();

        // Получаем друзей, где текущий пользователь получатель
        $receivedFriends = $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id')
            ->withPivot('status', 'created_at', 'updated_at') // Загружаем данные из таблицы friendships
            ->get();

        // Объединяем результаты и убираем дубликаты (если они есть)
        return $initiatedFriends->merge($receivedFriends)->unique('id');
    }

    // В модели User добавьте эти связи:
    public function friendshipsInitiated(): HasMany
    {
        return $this->hasMany(Friendships::class, 'user_id');
    }

    public function friendshipsReceived(): HasMany
    {
        return $this->hasMany(Friendships::class, 'friend_id');
    }

    public function acceptedFriends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
            ->wherePivot('status', 'accepted')
            ->withTimestamps();
    }

    public function pendingFriends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id')
            ->wherePivot('status', 'pending')
            ->withTimestamps();
    }

    public function chat_room_user(): HasMany
    {
        return $this->hasMany(ChatRoomUser::class, 'user_id', 'id', '');
    }

    public function chatRooms(): BelongsToMany
    {
        return $this->belongsToMany(ChatRoom::class, 'chat_room_users', 'user_id', 'chat_room_id')
            ->withPivot('joined_via', 'joined_at', 'user_id', 'chat_room_id')
            ->withTimestamps();
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(UserReactions::class);
    }

    public function isBlocked(): bool
    {
        return $this->is_blocked;
    }
}
