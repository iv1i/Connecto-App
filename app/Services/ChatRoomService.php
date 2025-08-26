<?php

namespace App\Services;

use App\Events\RoomsEvent;
use App\Http\Requests\ChatRoomRequest;
use App\Http\Requests\UpdateChatRoomRequest;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;


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
            $data['invite_code'] = Utility::generateInviteCode($user->id);
        }
        $data['actions'] = $actions;
        //$room = ChatRoom::create($data);

        $room = $user->chatRooms()->create($data);

        return $room;
    }

    public function updateRoom(UpdateChatRoomRequest $request, ChatRoom $room): ChatRoom
    {
        $authUser = auth()->user();
        $authUserId = $authUser->id;

        $data = $request->validated();
        if ($authUser->cannot('update', $room)) {
            abort(403, 'Only owner can update chat rooms');
        }
        if (isset($data['type'])) {
            if ($data['type'] === ChatRoom::TYPE_PRIVATE) {
                $data['invite_code'] = Utility::generateInviteCode($authUserId);
            }
            if ($data['type'] === ChatRoom::TYPE_PUBLIC) {
                $data['invite_code'] = null;
            }
        }

        $room->update($data);

        return $room;
    }

    public function deleteRoom(ChatRoom $room): array
    {
        $authUser = auth()->user();
        $authUserId = $authUser->id;

        if ($room->isPersonal()) {
            $member = $room->members()->where('users.id', $authUserId)->exists();
            if ($member) {
                $room->delete();
            }

            RoomsEvent::dispatch(true);

            return ['message' => 'the room was successfully deleted'];
        }
        if ($room->created_by === $authUserId) {
            $room->delete();
            RoomsEvent::dispatch(true);

            return ['message' => 'the room was successfully deleted'];
        }

        return ['error' => 'not enough rights'];
    }

    public function getPublicRooms(): LengthAwarePaginator
    {
        $authUser = auth()->user();
        $authUserId = $authUser->id;

        $query = ChatRoom::with(['creator', 'members'])
            ->where('type', ChatRoom::TYPE_PUBLIC)
            ->withCount('messages');

        // Если пользователь авторизован, исключаем комнаты, где он создатель или участник
        if (auth('sanctum')->check()) {
            $query->where('created_by', '!=', $authUserId)
                ->whereDoesntHave('members', function($q) use ($authUserId) {
                    $q->where('user_id', $authUserId);
                });
        }

        return $query->paginate(10);
    }

    public function joinByInviteCode(Request $request): ChatRoom|array
    {
        $authUser = auth()->user();

        $code = $request->input('code');
        $room = ChatRoom::getRoomByInviteCode($code);

        if (!$room) {
            return ['message' => 'Room not found'];
        }

        // Присоединяем пользователя к комнате
        $authUser->chatRooms()->syncWithoutDetaching([
            $room->id => ['joined_via' => 'invite_code']
        ]);

        return $room;
    }

    public function createPersonalRoom(User $user): ChatRoom|array
    {
        $authUser = auth()->user();
        $authUserId = $authUser->id;

        $checkFriend = $user->friendshipsInitiated()->where('friend_id', $authUserId)->exists();

        if (!$checkFriend) {
            return ['message' => 'Friend not found'];
        }

        $hexSum = Utility::hexSumStrings($user->link_name, $authUser->link_name);

        $hash = md5($hexSum);

        $room = ChatRoom::where('personal_chat', $hash)->first();

        if (!$room) {
            $actions = [
                'delete' => 'all',
                'invite' => 'none',
                'update' => 'all'
            ];
            $data = [];
            $data['name'] = $user->name .'|'. $authUser->name;
            $data['created_by'] = $authUserId;
            $data['type'] = ChatRoom::TYPE_PERSONAL;
            $data['invite_code'] = null;
            $data['actions'] = $actions;
            $data['personal_chat'] = md5($hexSum);
            $data['description'] = 'Personal chat';

            $chatRoom = ChatRoom::create($data);

            $user->chatRooms()->syncWithoutDetaching([
                $chatRoom->id => ['joined_via' => 'friend-personal-chat']
            ]);

            $authUser->chatRooms()->syncWithoutDetaching([
                $chatRoom->id => ['joined_via' => 'friend-personal-chat']
            ]);

            RoomsEvent::dispatch(true);

            return $chatRoom;
        }
        else {
            if ($user->chatRooms()->find($room->id)) {
                $authUser->chatRooms()->syncWithoutDetaching([
                    $room->id => ['joined_via' => 'friend-personal-chat']
                ]);
            }
            return $room;
        }
    }

    public function joinRoom(ChatRoom $room): array
    {
        $authUser = auth()->user();

        $checkRoom = $authUser->chatRooms()->where('chat_rooms.id', $room->id)->exists();
        if (!$checkRoom) {
            if ($room->isPublic()) {
                // Присоединяем пользователя к комнате
                $authUser->chatRooms()->attach(
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
        $authUser = auth()->user();

        // Проверяем, является ли комната персональной
        if ($room->isPersonal()) {
            // Удаляем текущего пользователя из комнаты
            $authUser->chatRooms()->detach($room);

            // Проверяем, остались ли еще участники в комнате
            $remainingMembers = $room->members()->count();

            // Если участников не осталось, удаляем комнату
            if ($remainingMembers === 0) {
                $room->delete();

                return ['message' => 'Successfully logged out and room deleted (no members left)'];
            }

            return ['message' => 'Successfully logged out from personal chat'];
        }
        $chekRooms = $authUser->chat_room_user()->where('chat_room_id', $room->id)->exists();
        if ($chekRooms && !$authUser->isOwnerRoom($room)) {
            $authUser->chatRooms()->detach($room);

            return ['message' => 'Successfully logged out'];
        }

        return ['error' => 'The user is not here'];
    }

    public function getJoinedRooms(): array
    {
        $authUser = auth()->user();

        $joinedRooms = $authUser->chatRooms()->withCount('messages')->orderByDesc('type')->get();
        //dd(collect($joinedRooms)->isEmpty());
        if ($joinedRooms) {
            return $joinedRooms;
        }

        return ['message' => 'You don\'t have rooms'];
    }

    public function showRoom(ChatRoom $room, User $user): array
    {
        $authUser = auth()->user();
        $authUserId = $authUser->id;

        if ($room->isPrivate() || $room->isPersonal()) {
            $member = $room->isMember($authUserId);
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
        $authUser = auth()->user();
        $authUserId = $authUser->id;

        $query = ChatRoom::where('name', 'like', "%{$query}%")
            ->where('type', ChatRoom::TYPE_PUBLIC)
            ->withCount('messages');

        // Если пользователь авторизован, исключаем комнаты, где он создатель или участник
        if (auth('sanctum')->check()) {
            $query->where('created_by', '!=', $authUser->id)
                ->whereDoesntHave('members', function($q) use ($authUserId) {
                    $q->where('user_id', $authUserId);
                });
        }

        return $query->paginate(10);
    }

}
