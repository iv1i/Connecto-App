<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UsersResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    public function profile(): UsersResource
    {
        $user = $this->userService->getProfile();

        return new UsersResource($user);
    }

    public function index(): AnonymousResourceCollection
    {
        $users = $this->userService->getAllUsers();

        return UsersResource::collection($users);
    }

    public function show(User $user): UsersResource
    {
        return new UsersResource($user);
    }

    public function search(SearchRequest $request): AnonymousResourceCollection
    {
        $query = $request->input('query');
        $users = $this->userService->searchUsers($query);

        return UsersResource::collection($users);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse|UsersResource
    {
        $resp = $this->userService->updateUser($request, $user);

        if ($resp['error']) {
            return response()->json($resp, $resp['status']);
        }

        return new UsersResource($user);
    }

    public function block(User $user): UsersResource
    {
        $user = $this->userService->blockUser($user);

        return new UsersResource($user);
    }

    public function unblock(User $user): UsersResource
    {
        $user = $this->userService->unblockUser($user);

        return new UsersResource($user);
    }
}
