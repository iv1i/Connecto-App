<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateFriendshipsRequest;
use App\Http\Resources\UsersResource;
use App\Models\Friendships;
use App\Models\User;
use App\Services\FriendshipsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class FriendshipsController extends Controller
{
    public function __construct(private FriendshipsService $friendshipsService)
    {
    }
    public function index(): AnonymousResourceCollection
    {
        $friends = $this->friendshipsService->getFriends();

        return UsersResource::collection($friends);
    }

    public function store(User $user): JsonResponse
    {
        $friend = $this->friendshipsService->storeFriends($user);

        return new JsonResponse($friend);
    }

    public function pending(): AnonymousResourceCollection
    {
        $pendingFriends = $this->friendshipsService->getPendingFriends();

        return UsersResource::collection($pendingFriends);
    }

    public function update(User $user, $command): JsonResponse|UsersResource
    {
        $resp = $this->friendshipsService->updateFriends($user, $command);

        if (isset($resp['error'])) {
            return response()->json($resp, 404);
        }
        if (isset($resp['message'])) {
            return response()->json($resp);
        }

        return new UsersResource($resp);
    }

    public function destroy(User $user): JsonResponse
    {
        $resp = $this->friendshipsService->deleteFriends($user);

        return response()->json($resp);
    }
}
