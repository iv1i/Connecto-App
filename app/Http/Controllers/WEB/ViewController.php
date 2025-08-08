<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function loginView(Request $request)
    {
        return view('auth.login');
    }

    public function registerView(Request $request)
    {
        return view('auth.register');
    }

    public function chatView()
    {
        return view('chat.index');
    }
}
