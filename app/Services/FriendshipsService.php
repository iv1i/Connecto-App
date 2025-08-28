<?php

namespace App\Services;

use App\Models\Friendships;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\JsonResponse;

class FriendshipsService
{
    public function getFriends(): Collection
    {
        return auth()->user()->acceptedFriends()->withPivot('status', 'created_at', 'updated_at')->get();
    }

    public function storeFriends(User $user): array
    {
        // Проверяем существование любой дружбы (любого статуса)
        $existingFriendship = auth()->user()->friendshipsInitiated()->where('friend_id', $user->id)->first();

        if ($existingFriendship) {
            if ($existingFriendship->status === 'pending') {
                // Если запрос уже pending, возвращаем сообщение
                return ['message' => 'Friend request already sent'];
            } elseif ($existingFriendship->status === 'accepted') {
                // Если уже друзья
                return ['message' => 'Already friends'];
            }
        }
        auth()->user()->friends()->attach($user->id, ['status' => 'pending']);

        return ['message' => 'Friend request sent'];
    }

    public function getPendingFriends(): Collection
    {
        $pendingFriends = auth()->user()->pendingFriends()->withPivot('status', 'created_at', 'updated_at')->get();

        return $pendingFriends;
    }

    public function updateFriends(User $user, $command): array|User
    {
        if ($command === 'accept') {
            $friendship = auth()->user()->friendshipsReceived()
                ->where('user_id', $user->id)
                ->first();

            if (auth()->user()->isFriendWith($user)){
                return ['error' => 'You can\'t accept a friend request if you\'re already friends'];
            }

            if ($friendship) {
                if ($friendship->status === 'pending') {
                    $friendship->status = 'accepted';
                    $friendship->save();
                    auth()->user()->acceptedFriends()->attach($user->id, ['status' => 'accepted']);

                    return $friendship->friend()->get()[0];
                }
            }

            else{
                return ['error' => 'Friendships not found'];
            }
        }

        if ($command === 'deny') {
            $friendship = auth()->user()->friendshipsReceived()
                ->where('user_id', $user->id)
                ->first();

            if (auth()->user()->isFriendWith($user)){
                return ['error' => 'You can\'t cancel a friend request if you\'re already friends'];
            }

            if ($friendship) {
                // Удаляем запись у себя
                auth()->user()->friendshipsInitiated()
                    ->where('friend_id', $user->id)
                    ->delete();

                // Удаляем запись у друга
                $user->friendshipsInitiated()
                    ->where('friend_id', auth()->user()->id)
                    ->delete();

                return ['message' => 'Friend deny successfully'];
            }
            else{
                return ['error' => 'Friendships not found'];
            }
        }

        return ['error' => 'Unknown command: ' . $command];
    }

    public function deleteFriends(User $user): array
    {
        // Удаляем запись у себя
        auth()->user()->friendshipsInitiated()
            ->where('friend_id', $user->id)
            ->delete();

        // Удаляем запись у друга
        $user->friendshipsInitiated()
            ->where('friend_id', auth()->user()->id)
            ->delete();

        return ['message' => 'Friend removed successfully'];
    }
}
