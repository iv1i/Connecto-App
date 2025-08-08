<?php

use App\Http\Controllers\ViewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect('/login');
});


Route::get('/login', [ViewController::class, 'loginView'])->name('login');
Route::get('/register', [ViewController::class, 'registerView'])->name('register');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/chat', [ViewController::class, 'chatView'])->name('chat.index');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
