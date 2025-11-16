<?php

namespace App\Services;

use App\Models\ChatRoom;
use App\Models\User;

class UnreadMessageService
{
    public function markAsRead(ChatRoom $room, User $user): array
    {
        $user->chatRooms()->updateExistingPivot($room->id, [
            'last_read_at' => now()
        ]);

        return [
            'success' => true,
            'unread_count' => 0
        ];
    }

    public function getUnreadCounts(User $user): array
    {
        $unreadCounts = [];

        $user->chatRooms->each(function($room) use ($user, &$unreadCounts) {
            $unreadCounts[$room->id] = $room->unreadMessagesCountForUser($user->id);
        });

        return $unreadCounts;
    }

    public function getTotalUnreadCount(User $user): float|int
    {
        return array_sum($this->getUnreadCounts($user));
    }
}