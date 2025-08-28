<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ChatRoomController;
use App\Http\Controllers\API\FriendshipsController;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/rooms', [ChatRoomController::class, 'index']);
Route::get('/rooms/search', [ChatRoomController::class, 'search']);
Route::get('/users/search', [UserController::class, 'search']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Friends
    Route::get('/friends', [FriendshipsController::class, 'index']);
    Route::get('/friends/pending', [FriendshipsController::class, 'pending']);
    Route::post('/friends/{user}', [FriendshipsController::class, 'store']);
    Route::put('/friends/{user}/{command}', [FriendshipsController::class, 'update']);
    Route::delete('/friends/{user}', [FriendshipsController::class, 'destroy']);

    // Rooms
    Route::get('/rooms/joined', [ChatRoomController::class, 'joined']);
    Route::get('/rooms/{room}', [ChatRoomController::class, 'show']);
    Route::post('/rooms/{room}/join', [ChatRoomController::class, 'join']);
    Route::post('/rooms/{room}/logout', [ChatRoomController::class, 'logout']);
    Route::post('/rooms', [ChatRoomController::class, 'store']);
    Route::post('/rooms/friend/{user}', [ChatRoomController::class, 'personal']);
    Route::post('/rooms/join', [ChatRoomController::class, 'invite']);
    Route::put('/rooms/{room}', [ChatRoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{room}', [ChatRoomController::class, 'destroy']);

    // Messages
    Route::get('/rooms/{room}/messages', [MessageController::class, 'index']);
    Route::post('/messages', [MessageController::class, 'store']);
    Route::post('/messages/{message}/react/{reaction}', [MessageController::class, 'addReaction']);
    Route::delete('/messages/{message}', [MessageController::class, 'destroy']);
    Route::delete('/messages/{message}/react/{reaction}', [MessageController::class, 'delReaction']);

    // Users
    Route::get('/profile', [UserController::class, 'profile']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);

    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::post('/users/{user}/block', [UserController::class, 'block']);
        Route::post('/users/{user}/unblock', [UserController::class, 'unblock']);
    });
});
