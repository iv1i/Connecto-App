<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatRoom extends Model
{
    use HasFactory;

    const TYPE_PUBLIC = 'public';
    const TYPE_PRIVATE = 'private';
    const TYPE_PERSONAL = 'personal';

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
            self::TYPE_PERSONAL => 'Personal',
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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'chat_room_users')
            ->withPivot('joined_via', 'joined_at', 'last_read_at')
            ->withTimestamps();
    }

    public function unreadMessagesCountForUser($userId): int
    {
        $lastRead = $this->members()->where('user_id', $userId)->first()->pivot->last_read_at;

        if (!$lastRead) {
            // Если никогда не читал, считаем все сообщения кроме своих
            return $this->messages()->where('user_id', '!=', $userId)->count();
        }

        // Считаем только сообщения других пользователей после last_read_at
        return $this->messages()
            ->where('created_at', '>', $lastRead)
            ->where('user_id', '!=', $userId)
            ->count();
    }

    public static function getRoomByInviteCode(string $code)
    {
        return self::where('invite_code', $code)->first();
    }

    public function roomExistsByInviteCode(string $code): bool
    {
        return $this->where('invite_code', $code)->exists();
    }

    public function isMember($userId)
    {
        return $this->members()->where('users.id', $userId)->exists();
    }

    public function isPublic(): bool
    {
        return $this->type === self::TYPE_PUBLIC;
    }

    public function isPrivate(): bool
    {
        return $this->type === self::TYPE_PRIVATE;
    }

    public function isPersonal(): bool
    {
        return $this->personal_chat !== null && $this->type === self::TYPE_PERSONAL;
    }
}
