<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function loginView(Request $request)
    {
        if ($request->user()){
            return redirect('/chat');
        }
        return view('auth.login');
    }

    public function registerView(Request $request)
    {
        if ($request->user()){
            return redirect('/chat');
        }
        return view('auth.register');
    }

    public function chatView()
    {
        return view('chat.index');
    }
}
