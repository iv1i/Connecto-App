<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatRoomRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UpdateChatRoomRequest;
use App\Models\ChatRoom;
use App\Models\User;
use App\Services\ChatRoomService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ChatRoomController extends Controller
{
    public function __construct(private ChatRoomService $chatRoomService)
    {
    }

    public function index(): JsonResponse
    {
        $rooms = $this->chatRoomService->getPublicRooms();

        return response()->json($rooms);
    }

    public function joined(): JsonResponse
    {
        $rooms = $this->chatRoomService->getJoinedRooms();

        return response()->json($rooms);
    }

    public function store(ChatRoomRequest $request): JsonResponse
    {
        $room = $this->chatRoomService->createRoom($request, Auth::user());

        return response()->json($room, 201);
    }

    public function show(ChatRoom $room): JsonResponse
    {
        $resp = $this->chatRoomService->showRoom($room, Auth::user());

        return response()->json($resp);
    }

    public function update(UpdateChatRoomRequest $request, ChatRoom $room): JsonResponse
    {
        $room = $this->chatRoomService->updateRoom($request, $room);

        return response()->json($room);
    }

    public function destroy(ChatRoom $room): JsonResponse
    {
        $resp = $this->chatRoomService->deleteRoom($room);

        return response()->json($resp);
    }

    public function join(ChatRoom $room)
    {
        $rooms = $this->chatRoomService->joinRoom($room);

        return response()->json($rooms);
    }

    public function search(SearchRequest $request): JsonResponse
    {
        $query = $request->input('query');
        $rooms = $this->chatRoomService->searchRooms($query);

        return response()->json($rooms);
    }

    public function invite(Request $request): JsonResponse
    {
        $room = $this->chatRoomService->joinByInviteCode($request);

        return response()->json($room);
    }

    public function logout(ChatRoom $room): JsonResponse
    {
        $this->chatRoomService->logoutRoom($room);

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function personal(User $user): JsonResponse
    {
        $room = $this->chatRoomService->createPersonalRoom($user);

        return response()->json($room);
    }

}
