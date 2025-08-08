<?php

use App\Http\Controllers\WEB\AuthController;
use App\Http\Controllers\WEB\ViewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});


Route::get('/login', [ViewController::class, 'loginView'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [ViewController::class, 'registerView'])->name('register');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/chat', [ViewController::class, 'chatView'])->name('chat.index');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
