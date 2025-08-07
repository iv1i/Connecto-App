<?php

namespace App\Observers;

use App\Models\ChatRoom;
use Illuminate\Support\Facades\Log;

class ChatRoomObserver
{
    public function created(ChatRoom $chatRoom): void
    {
        Log::info("Chat room created: {$chatRoom->name} by user {$chatRoom->created_by}");
    }

    public function updated(ChatRoom $chatRoom): void
    {
        Log::info("Chat room updated: {$chatRoom->name}");
    }

    public function deleted(ChatRoom $chatRoom): void
    {
        Log::info("Chat room deleted: {$chatRoom->name}");
    }
}
