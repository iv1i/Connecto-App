<?php

namespace App\Services;

use App\Http\Requests\UpdateUserRequest;
use App\Models\Friendships;
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
        $authUser = auth()->user();

        if ($authUser->can('update', $user)) {
            abort(403, 'No access');
        }
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        if (isset($data['password'])) {
            // Для обычных пользователей (не админов) проверяем current_password
            if (!$authUser->isAdmin()) {
                // Проверяем наличие current_password
                if (!isset($data['current_password'])) {
                    abort(403, 'Current password is required');
                }

                // Проверяем соответствие текущего пароля
                if (!Hash::check($data['current_password'], $user->password)) {
                    abort(403, 'Current password does not match');
                }
            }

            // Хешируем новый пароль
            $data['password'] = Hash::make($data['password']);

            // Удаляем все текущие токены пользователя
            $user->tokens()->delete();
        }
        if (isset($data['role'])) {
            if ($data['role'] === 'admin' && !$authUser->isAdmin()) {
                abort(403, 'Admin only');
            }
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
        $authUser = auth()->user();
        $authUserId = $authUser->id;

        $query = User::where('id', '!=', $authUserId);

        if (auth('sanctum')->check()) {
            // Получаем ID друзей через подзапрос для оптимизации
            $friendIds = Friendships::where('user_id', $authUserId)
                ->pluck('friend_id');

            // Исключаем друзей
            $query->whereNotIn('id', $friendIds);
        }

        return $query->paginate(10);
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
