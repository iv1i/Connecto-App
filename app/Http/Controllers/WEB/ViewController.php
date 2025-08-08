<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function loginView(Request $request)
    {
        if (auth()->check()) {
            return redirect()->route('chat.index');
        }
        return view('auth.login');
    }

    public function registerView(Request $request)
    {
        if (auth()->check()) {
            return redirect()->route('chat.index');
        }
        return view('auth.register');
    }

    public function chatView()
    {
        return view('chat.index');
    }
}
