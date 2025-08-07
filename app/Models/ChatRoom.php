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
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function getTypes(): array
    {
        return [
            self::TYPE_PUBLIC => 'Public',
            self::TYPE_PRIVATE => 'Private',
        ];
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isPublic(): bool
    {
        return $this->type === self::TYPE_PUBLIC;
    }

    public function isPrivate(): bool
    {
        return $this->type === self::TYPE_PRIVATE;
    }
}
