<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ChatRoom;
use App\Services\UnreadMessageService;
use Illuminate\Http\Request;

class UnreadMessageController extends Controller
{
    public function __construct(
        private UnreadMessageService  $unreadMessageService
    ) {
    }
    public function markAsRead(ChatRoom $room)
    {
        return response()->json($this->unreadMessageService->markAsRead($room, auth()->user()));
    }

    public function getUnreadCounts()
    {
        return response()->json($this->unreadMessageService->getUnreadCounts(auth()->user()));
    }

    public function getTotalUnreadCount()
    {
        return response()->json([
            'total_unread' => $this->unreadMessageService->getTotalUnreadCount(auth()->user())
        ]);
    }
}