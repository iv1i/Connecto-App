<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReactions extends Model
{
    /** @use HasFactory<\Database\Factories\UserReactionsFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message_id',
        'reaction'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
