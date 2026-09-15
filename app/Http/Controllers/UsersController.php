<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class UsersController extends Controller
{
    public function home()
    {
        return 'Hola, UsersController, / (home)';
    }

    public function signup()
    {
        return 'Hola, UsersController, /registrarse';
    }

    public function login()
    {
        return 'Hola, UsersController, /ingresar';
    }

    public function logout()
    {
        return 'Hola, UsersController, /cerrar-sesion';
    }
}
