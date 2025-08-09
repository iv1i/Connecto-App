<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ChatRoomController;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/login', function () {return response()->json(['message' => 'Unauthenticated'], 403);})->name('api.login');
Route::get('/rooms', [ChatRoomController::class, 'index']);
Route::get('/rooms/search', [ChatRoomController::class, 'search']);
Route::get('/rooms/{room}', [ChatRoomController::class, 'show']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Chat rooms
    Route::post('/rooms', [ChatRoomController::class, 'store']);
    Route::put('/rooms/{room}', [ChatRoomController::class, 'update']);
    Route::delete('/rooms/{room}', [ChatRoomController::class, 'destroy']);
    Route::post('/rooms/join', [ChatRoomController::class, 'joinByInviteCode']);

    // Messages
    Route::get('/rooms/{room}/messages', [MessageController::class, 'index']);
    Route::post('/messages', [MessageController::class, 'store']);
    Route::delete('/messages/{message}', [MessageController::class, 'destroy']);
    Route::post('/messages/{message}/react/{reaction}', [MessageController::class, 'addReaction']);
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
