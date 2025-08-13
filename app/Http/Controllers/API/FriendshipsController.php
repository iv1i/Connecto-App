<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateFriendshipsRequest;
use App\Models\Friendships;
use App\Models\User;

class FriendshipsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $friends = auth()->user()->acceptedFriends()->withPivot('status', 'created_at', 'updated_at')->get();

            return response()->json([
                'success' => true,
                'data' => $friends,
                'count' => $friends->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch friends list',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(User $user)
    {
        $checkFriend = $user->acceptedFriends()->where('friend_id', auth()->user()->id)->exists();
        if ($checkFriend) {
            $friend = auth()->user()->acceptedFriends()->syncWithoutDetaching([
                $user->id => ['status' => 'accepted']
            ]);
            return response()->json($friend);
        }
        $friend = auth()->user()->acceptedFriends()->syncWithoutDetaching([
            $user->id => ['status' => 'pending']
        ]);
        return response()->json($friend);
    }

    public function pending()
    {

        $pendingFriends = auth()->user()->pendingFriends()->withPivot('status', 'created_at', 'updated_at')->get();
        return response()->json([
            'success' => true,
            'data' => $pendingFriends,
            'count' => $pendingFriends->count()
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Friendships $friendships)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Friendships $friendships)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(User $user)
    {
        $friendship = auth()->user()->friendshipsReceived()
            ->where('user_id', $user->id)
            ->first();
        //dd(['v1.0.0',$friendship]);
        if ($friendship) {
            if ($friendship->status === 'pending') {
                $friendship->status = 'accepted';
                $friendship->save();
                auth()->user()->acceptedFriends()->attach($user->id, ['status' => 'accepted']);
                return response()->json($friendship->friend()->get());
            }
        }
        else{
            return response()->json(['error' => 'Friendships not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Удаляем запись у себя
        auth()->user()->friendshipsInitiated()
            ->where('friend_id', $user->id)
            ->delete();

        // Удаляем запись у друга
        $user->friendshipsInitiated()
            ->where('friend_id', auth()->user()->id)
            ->delete();

        return response()->json(['message' => 'Friend removed successfully'], 200);
    }
}
