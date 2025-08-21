<?php

use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('send-messages.{roomId}', function (User $user, $roomId) {
    return auth()->check() && $user->chatRooms()->where('chat_rooms.id', $roomId)->exists();
}, ['guards' => ['web', 'sanctum']]);

Broadcast::channel('deleted-message', function () {
    return auth()->check();
}, ['guards' => ['web', 'sanctum']]);

Broadcast::channel('reaction-add', function () {
    return auth()->check();
}, ['guards' => ['web', 'sanctum']]);

Broadcast::channel('rooms-event', function () {
    return auth()->check();
}, ['guards' => ['web', 'sanctum']]);


