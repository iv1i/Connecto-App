<?php

namespace App\Services;

use App\Http\Requests\UpdateUserRequest;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{

    public function getProfile(): User
    {
        return auth()->user();
    }
    public function updateUser(UpdateUserRequest $request, User $user): User
    {
        if (auth()->user()->id !== $user->id && !auth()->user()->isAdmin()) {
            abort(403, 'No access');
        }
        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        return $user;
    }

    public function searchUsers(string $query): LengthAwarePaginator
    {
        return User::where('name', 'like', "%{$query}%")
            ->where('role', 'user')
            ->where('is_blocked', 0)
            ->paginate(10);
    }

    public function getAllUsers(): LengthAwarePaginator
    {
        return User::paginate(15);
    }

    public function blockUser(User $user): User
    {
        $user->update(['is_blocked' => true]);
        return $user;
    }

    public function unblockUser(User $user): User
    {
        $user->update(['is_blocked' => false]);
        return $user;
    }
}
