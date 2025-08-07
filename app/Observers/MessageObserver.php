<?php

namespace App\Observers;

use App\Models\Message;
use Illuminate\Support\Facades\Log;

class MessageObserver
{
    public function created(Message $message): void
    {
        Log::info("Message sent by {$message->user_id} in room {$message->chat_room_id}");
    }

    public function updated(Message $message): void
    {
        Log::info("Message updated: {$message->id}");
    }

    public function deleted(Message $message): void
    {
        Log::info("Message deleted: {$message->id}");
    }
}
