<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoomUser extends Model
{
    /** @use HasFactory<\Database\Factories\ChatRoomUserFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'chat_room_id',
        'joined_via'
    ];

}
