<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales ingresadas son incorrectas.'],
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function signUpStore(SignupRequest $request)
    {
        $user = DB::transaction(function () use ($request) {
            return User::create([
                'NombreUsuario' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'Sexo' => $request->gender,
                'Avatar_URL' => "https://api.dicebear.com/10.x/shadows/svg?backgroundColor=16161a&inkColor=e6e2dd,d8dfe6,e6dde2,dee6d8&seed=" . $request->name,
                'telefono' => $request->telefono ?? null
            ]);
        });

        Auth::login($user);

        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
