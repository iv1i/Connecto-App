<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('send-messages.{roomId}', function (User $user, $roomId) {
    return auth()->guard('sanctum')->check() && $user->chatRooms()->where('chat_rooms.id', $roomId)->exists();
}, ['guards' => ['sanctum']]);

Broadcast::channel('deleted-message', function () {
    return auth()->guard('sanctum')->check();
}, ['guards' => ['sanctum']]);

Broadcast::channel('reaction-add', function () {
    return auth()->guard('sanctum')->check();
}, ['guards' => ['sanctum']]);

Broadcast::channel('rooms-event', function () {
    return auth()->guard('sanctum')->check();
}, ['guards' => ['sanctum']]);


