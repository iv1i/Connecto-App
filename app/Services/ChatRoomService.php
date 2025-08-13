<?php

namespace App\Services;

use App\Http\Requests\ChatRoomRequest;
use App\Models\ChatRoom;
use App\Models\ChatRoomUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use function PHPUnit\Framework\isEmpty;

class ChatRoomService
{
    public function createRoom(ChatRoomRequest $request, User $user): ChatRoom
    {
        $actions = [
            'delete' => 'owner',
            'invite' => 'none',
            'update' => 'owner'
        ];

        $data = $request->validated();

        $data['created_by'] = $user->id;
        if ($data['type'] === ChatRoom::TYPE_PRIVATE) {
            $actions = [
                'delete' => 'owner',
                'invite' => 'owner',
                'update' => 'owner'
            ];
            $data['invite_code'] = Str::random(32);
        }
        $data['actions'] = $actions;
        //$room = ChatRoom::create($data);

        $room = $user->chatRooms()->create($data);

        return $room;
    }

    public function updateRoom(ChatRoomRequest $request, ChatRoom $room): ChatRoom
    {
        $room->update($request->validated());
        return $room;
    }

    public function deleteRoom(ChatRoom $room): array
    {
        if ($room->isPersonal()) {
            $member = $room->members()->where('users.id', Auth::id())->exists();
            if ($member) {
                $room->delete();
            }
            return ['message' => 'the room was successfully deleted'];
        }
        if ($room->created_by === Auth::id()) {
            $room->delete();
            return ['message' => 'the room was successfully deleted'];
        }
        return ['error' => 'not enough rights'];
    }


    public function getPublicRooms()
    {
        $query = ChatRoom::with(['creator', 'members'])
            ->where('type', ChatRoom::TYPE_PUBLIC)
            ->withCount('messages');

        // Если пользователь авторизован, исключаем комнаты, где он создатель или участник
        if (auth('sanctum')->check()) {
            $userId = auth('sanctum')->id();

            $query->where('created_by', '!=', $userId)
                ->whereDoesntHave('members', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
        }

        return $query->paginate(10);
    }

    public function joinByInviteCode(Request $request): ChatRoom|array
    {
        $code = $request->input('code');
        $room = ChatRoom::getRoomByInviteCode($code);

        if (!$room) {
            return ['message' => 'Room not found'];
        }

        // Присоединяем пользователя к комнате
        auth()->user()->chatRooms()->syncWithoutDetaching([
            $room->id => ['joined_via' => 'invite_code']
        ]);

        return $room;
    }

    public function createPersonalRoom(User $user): ChatRoom|array
    {
        $checkFriend = $user->friendshipsInitiated()->where('friend_id', auth()->user()->id)->exists();

        if (!$checkFriend) {
            return ['message' => 'Friend not found'];
        }

        $hexSum = Utility::hexSumStrings($user->link_name, auth()->user()->link_name);

        $hash = md5($hexSum);

        $room = ChatRoom::where('personal_chat', $hash)->first();

        if (!$room) {
            $actions = [
                'delete' => 'all',
                'invite' => 'none',
                'update' => 'all'
            ];
            $data = [];
            $data['name'] = $user->name .'|'. auth()->user()->name;
            $data['created_by'] = auth()->user()->id;
            $data['type'] = ChatRoom::TYPE_PRIVATE;
            $data['invite_code'] = null;
            $data['actions'] = $actions;
            $data['personal_chat'] = md5($hexSum);
            $data['description'] = 'Personal chat';

            $chatRoom = ChatRoom::create($data);

            $user->chatRooms()->syncWithoutDetaching([
                $chatRoom->id => ['joined_via' => 'friend-personal-chat']
            ]);

            auth()->user()->chatRooms()->syncWithoutDetaching([
                $chatRoom->id => ['joined_via' => 'friend-personal-chat']
            ]);

            return $chatRoom;
        }
        else {
            if ($user->chatRooms()->find($room->id)) {
                auth()->user()->chatRooms()->syncWithoutDetaching([
                    $room->id => ['joined_via' => 'friend-personal-chat']
                ]);
            }
            return $room;
        }
    }

    public function joinRoom(ChatRoom $room): array
    {
        $checkRoom = auth()->user()->chatRooms()->where('chat_rooms.id', $room->id)->exists();
        if (!$checkRoom) {
            if ($room->type !== 'private') {
                // Присоединяем пользователя к комнате
                auth()->user()->chatRooms()->attach(
                    $room->id, ['joined_via' => 'click_join']
                );
                return [
                    'message' => 'You have successfully joined the room',
                    'room' => $room
                ];
            }
            else {
                return [
                    'error' => 'Join denied. Invitation-only access',
                ];
            }
        }
        return [
            'message' => 'You are already joined this room',
            'room' => $room
        ];
    }

    public function logoutRoom(ChatRoom $room): array
    {
        if ($room->isPersonal()) {

        }
        $chekRooms = auth()->user()->chat_room_user()->where('chat_room_id', $room->id)->exists();
        if ($chekRooms) {
            auth()->user()->chatRooms()->detach($room);
            return ['message' => 'Successfully logged out'];
        }
        return ['error' => 'The user is not here'];
    }

    public function getJoinedRooms()
    {
        $joinedRooms = auth()->user()->chatRooms()->withCount('messages')->orderByDesc('type')->get();
        //dd(collect($joinedRooms)->isEmpty());
        if ($joinedRooms) {
            return $joinedRooms;
        }
        return ['message' => 'You don\'t have rooms'];
    }

    public function showRoom(ChatRoom $room, User $user): array
    {
        if ($room->isPrivate()) {
            $member = $room->members()->where('users.id', Auth::id())->exists();
            if ($member) {
                return [
                    'message' => 'access is allowed',
                    'room' => $room,
                ];
            }
            return ['error' => 'You are not in this room'];
        }
        return [
            'room' => $room
        ];
    }


    public function searchRooms(string $query): LengthAwarePaginator
    {
        return ChatRoom::where('name', 'like', "%{$query}%")
            ->where('type', ChatRoom::TYPE_PUBLIC)
            ->where('created_by','!=', Auth::id())
            ->withCount('messages')
            ->paginate(10);
    }

}
