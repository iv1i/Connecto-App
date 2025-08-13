<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatRoom extends Model
{
    use HasFactory;

    const TYPE_PUBLIC = 'public';
    const TYPE_PRIVATE = 'private';

    protected $fillable = [
        'name',
        'description',
        'type',
        'created_by',
        'invite_code',
        'personal_chat',
        'actions'
    ];

    protected $hidden = [
        'personal_chat'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'actions' => 'array',
    ];

    public static function getTypes(): array
    {
        return [
            self::TYPE_PUBLIC => 'Public',
            self::TYPE_PRIVATE => 'Private',
        ];
    }

    public function chat_room_users()
    {
        return $this->hasMany(ChatRoomUser::class, 'chat_room_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isMember($userId)
    {
        return $this->users()->where('user_id', $userId)->exists();
    }


    public function members()
    {
        return $this->belongsToMany(User::class,'chat_room_users')
            ->withPivot('joined_via', 'joined_at')
            ->withTimestamps();
    }

    public function isPublic(): bool
    {
        return $this->type === self::TYPE_PUBLIC;
    }


    public function getRoomByInviteCode(string $code)
    {
        return $this->where('invite_code', $code)->first();
    }

    public function roomExistsByInviteCode(string $code): bool
    {
        return $this->where('invite_code', $code)->exists();
    }

    public function isPrivate(): bool
    {
        return $this->type === self::TYPE_PRIVATE;
    }

    public function isPersonal(): bool
    {
        return $this->personal_chat !== null && $this->type === self::TYPE_PRIVATE;
    }
}
