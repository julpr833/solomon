<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
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

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $status = Password::broker()->sendResetLink(
            $request->only('email'),
            function ($user, $token) {
                $user->notify(new ResetPasswordNotification($token));
            }
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['status' => 'Te enviamos un enlace para restablecer tu contraseña.'])
            : throw ValidationException::withMessages(['email' => [$this->passwordMessage($status)]]);
    }

    public function showResetForm(string $token)
    {
        return view('auth.reset-password', [
            'resetToken' => $token,
            'resetEmail' => request('email'),
        ]);
    }

    public function submitReset(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages(['email' => [$this->passwordMessage($status)]]);
        }

        return response()->json(['status' => $this->passwordMessage($status)]);
    }

    private function passwordMessage(string $status): string
    {
        return match ($status) {
            Password::PASSWORD_RESET => 'Tu contraseña fue actualizada. Ya podés iniciar sesión.',
            Password::INVALID_USER => 'El correo electrónico no pertenece a ninguna cuenta.',
            Password::INVALID_TOKEN => 'El enlace de recuperación es inválido o ya fue utilizado.',
            Password::RESET_THROTTLED => 'Se solicitó demasiado. Esperá un momento y volvé a intentar.',
            default => 'Ocurrió un error. Volvé a intentar.',
        };
    }
}
