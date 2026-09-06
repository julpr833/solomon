<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController
{
    public function home()
    {
        return "Hola, UsersController, / (home)";
    }

    public function signup()
    {
        return "Hola, UsersController, /registrarse";
    }

    public function login()
    {
        return "Hola, UsersController, /ingresar";
    }

    public function logout()
    {
        return "Hola, UsersController, /cerrar-sesion";
    }
}