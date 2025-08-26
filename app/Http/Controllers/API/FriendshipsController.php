<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateFriendshipsRequest;
use App\Models\Friendships;
use App\Models\User;
use App\Services\FriendshipsService;
use Illuminate\Http\JsonResponse;


class FriendshipsController extends Controller
{
    public function __construct(private FriendshipsService $friendshipsService)
    {
    }
    public function index(): JsonResponse
    {
        $friends = $this->friendshipsService->getFriends();

        return response()->json($friends);
    }

    public function store(User $user): JsonResponse
    {
        $friend = $this->friendshipsService->storeFriends($user);

        return response()->json($friend);
    }

    public function pending(): JsonResponse
    {
        $pendingFriend = $this->friendshipsService->getPendingFriends();

        return response()->json($pendingFriend);
    }

    public function update(User $user, $command): JsonResponse
    {
        $resp = $this->friendshipsService->updateFriends($user, $command);

        if (isset($resp['error'])) {
            return response()->json($resp, 404);
        }
        else {
            return response()->json($resp);
        }
    }

    public function destroy(User $user): JsonResponse
    {
        $resp = $this->friendshipsService->deleteFriends($user);

        return response()->json($resp);
    }
}
