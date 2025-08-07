<?php

namespace App\Services;

use App\Http\Requests\ChatRoomRequest;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ChatRoomService
{
    public function createRoom(ChatRoomRequest $request, User $user): ChatRoom
    {
        $data = $request->validated();
        $data['created_by'] = $user->id;

        if ($data['type'] === ChatRoom::TYPE_PRIVATE) {
            $data['invite_code'] = Str::random(32);
        }

        return ChatRoom::create($data);
    }

    public function updateRoom(ChatRoomRequest $request, ChatRoom $room): ChatRoom
    {
        $room->update($request->validated());
        return $room;
    }

    public function deleteRoom(ChatRoom $room): void
    {
        $room->delete();
    }

    public function getPublicRooms(): LengthAwarePaginator
    {
        return ChatRoom::where('type', ChatRoom::TYPE_PUBLIC)
            ->withCount('messages')
            ->paginate(10);
    }

    public function searchRooms(string $query): LengthAwarePaginator
    {
        return ChatRoom::where('name', 'like', "%{$query}%")
            ->where('type', ChatRoom::TYPE_PUBLIC)
            ->paginate(10);
    }

    public function getRoomByInviteCode(string $code): ?ChatRoom
    {
        return ChatRoom::where('invite_code', $code)->first();
    }
}
