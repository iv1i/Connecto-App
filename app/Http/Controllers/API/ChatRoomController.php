<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatRoomRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UpdateChatRoomRequest;
use App\Http\Resources\RoomsResource;
use App\Models\ChatRoom;
use App\Models\User;
use App\Services\ChatRoomService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;


class ChatRoomController extends Controller
{
    public function __construct(private ChatRoomService $chatRoomService)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        $rooms = $this->chatRoomService->getPublicRooms();

        return RoomsResource::collection($rooms);
    }

    public function joined(): AnonymousResourceCollection
    {
        $rooms = $this->chatRoomService->getJoinedRooms();

        return RoomsResource::collection($rooms);
    }

    public function store(ChatRoomRequest $request): RoomsResource
    {
        $room = $this->chatRoomService->createRoom($request, Auth::user());

        return new RoomsResource($room);
    }

    public function show(ChatRoom $room): JsonResponse|RoomsResource
    {
        $resp = $this->chatRoomService->showRoom($room, Auth::user());

        if ($resp['error']){
            return response()->json($resp);
        }

        return new RoomsResource($room);
    }

    public function update(UpdateChatRoomRequest $request, ChatRoom $room): RoomsResource
    {
        $room = $this->chatRoomService->updateRoom($request, $room);

        return new RoomsResource($room);
    }

    public function destroy(ChatRoom $room): JsonResponse
    {
        $resp = $this->chatRoomService->deleteRoom($room);

        return response()->json($resp);
    }

    public function join(ChatRoom $room): JsonResponse|RoomsResource
    {
        $resp = $this->chatRoomService->joinRoom($room);

        if ($resp['error']){
            return response()->json($resp);
        }

        return new RoomsResource($room);
    }

    public function search(SearchRequest $request): AnonymousResourceCollection
    {
        $query = $request->input('query');
        $rooms = $this->chatRoomService->searchRooms($query);

        return RoomsResource::collection($rooms);
    }

    public function invite(Request $request): JsonResponse|RoomsResource
    {
        $resp = $this->chatRoomService->joinByInviteCode($request);

        if ($resp['message']) {
            return response()->json($resp);
        }

        return new RoomsResource($resp);
    }

    public function logout(ChatRoom $room): JsonResponse
    {
        $this->chatRoomService->logoutRoom($room);

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function personal(User $user): RoomsResource
    {
        $room = $this->chatRoomService->createPersonalRoom($user);

        return new RoomsResource($room);
    }

}
