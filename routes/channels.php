<?php

use App\Models\ChatRoom;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('room.{id}', function (ChatRoom $room, $id) {
    return (bool)auth()::guard('api')->check();
});
