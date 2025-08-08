<?php

use App\Models\ChatRoom;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('room', function () {
    return auth()->check();
}, ['guards' => ['web', 'sanctum']]);

Broadcast::channel('deleted-message', function () {
    return auth()->check();
}, ['guards' => ['web', 'sanctum']]);

Broadcast::channel('reaction-add', function () {
    return auth()->check();
}, ['guards' => ['web', 'sanctum']]);
