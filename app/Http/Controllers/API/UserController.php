<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    public function profile(): JsonResponse
    {
        $user = $this->userService->getProfile();
        return response()->json($user);
    }

    public function index(): JsonResponse
    {
        $users = $this->userService->getAllUsers();
        return response()->json($users);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    public function search(SearchRequest $request): JsonResponse
    {
        $query = $request->input('query');
        $rooms = $this->userService->searchUsers($query);
        return response()->json($rooms);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $user = $this->userService->updateUser($request, $user);
        return response()->json($user);
    }

    public function block(User $user): JsonResponse
    {
        $user = $this->userService->blockUser($user);
        return response()->json($user);
    }

    public function unblock(User $user): JsonResponse
    {
        $user = $this->userService->unblockUser($user);
        return response()->json($user);
    }
}
