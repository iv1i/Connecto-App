<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::middleware('auth')->group(function () {
    Route::get('/chat', function () {
        return view('chat.index');
    });
});

// Handle auth redirects
Route::get('/home', function () {
    return redirect('/chat');
});
