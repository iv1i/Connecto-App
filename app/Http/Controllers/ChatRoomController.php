<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatRoomRequest;
use App\Models\ChatRoom;
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

    public function store(ChatRoomRequest $request): JsonResponse
    {
        $room = $this->chatRoomService->createRoom($request, Auth::user());
        return response()->json($room, 201);
    }

    public function show(ChatRoom $room): JsonResponse
    {
        return response()->json($room);
    }

    public function update(ChatRoomRequest $request, ChatRoom $room): JsonResponse
    {
        $room = $this->chatRoomService->updateRoom($request, $room);
        return response()->json($room);
    }

    public function destroy(ChatRoom $room): JsonResponse
    {
        $this->chatRoomService->deleteRoom($room);
        return response()->json(null, 204);
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->input('query');
        $rooms = $this->chatRoomService->searchRooms($query);
        return response()->json($rooms);
    }

    public function joinByInviteCode(Request $request): JsonResponse
    {
        $code = $request->input('code');
        $room = $this->chatRoomService->getRoomByInviteCode($code);

        if (!$room) {
            return response()->json(['message' => 'Room not found'], 404);
        }

        return response()->json($room);
    }
}
