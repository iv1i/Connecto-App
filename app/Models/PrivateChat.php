<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivateChat extends Model
{
    /** @use HasFactory<\Database\Factories\PrivateChatFactory> */
    use HasFactory;

    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'private_chat_users');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
