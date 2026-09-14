<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Routing\Controller;

class UsersController extends Controller
{
    public function home()
    {
        return view('landing');
    }

    public function signup()
    {
        return view('auth.signup');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function loginStore(LoginRequest $request)
    {
        // ...
    }

    public function logout()
    {
        return redirect()->route('home');
    }
}
