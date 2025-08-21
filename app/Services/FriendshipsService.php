<?php

namespace App\Services;

use App\Models\Friendships;
use App\Models\User;
use Symfony\Component\HttpFoundation\JsonResponse;

class FriendshipsService
{
    public function getFriends(): array
    {
        try {
            $friends = auth()->user()->acceptedFriends()->withPivot('status', 'created_at', 'updated_at')->get();

            return [
                'success' => true,
                'data' => $friends,
                'count' => $friends->count()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch friends list',
                'error' => $e->getMessage()
            ];
        }
    }

    public function storeFriends(User $user): User
    {
        $checkFriend = $user->acceptedFriends()->where('friend_id', auth()->user()->id)->exists();
        if ($checkFriend) {
            $friend = auth()->user()->acceptedFriends()->syncWithoutDetaching([
                $user->id => ['status' => 'accepted']
            ]);
            return $friend;
        }
        $friend = auth()->user()->acceptedFriends()->syncWithoutDetaching([
            $user->id => ['status' => 'pending']
        ]);
        return $friend;
    }

    public function getPendingFriends(): array
    {

        $pendingFriends = auth()->user()->pendingFriends()->withPivot('status', 'created_at', 'updated_at')->get();
        return [
            'success' => true,
            'data' => $pendingFriends,
            'count' => $pendingFriends->count()
        ];
    }

    public function updateFriends(User $user, $command)
    {
        if ($command === 'accept') {
            $friendship = auth()->user()->friendshipsReceived()
                ->where('user_id', $user->id)
                ->first();
            //dd(['v1.0.0',$friendship]);
            if ($friendship) {
                if ($friendship->status === 'pending') {
                    $friendship->status = 'accepted';
                    $friendship->save();
                    auth()->user()->acceptedFriends()->attach($user->id, ['status' => 'accepted']);
                    return $friendship->friend()->get();
                }
            }
            else{
                return ['error' => 'Friendships not found'];
            }
        }
        if ($command === 'deny') {
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
