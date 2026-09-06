<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HabitsController {
    public function dashboard()
    {
        return "Hola, HabitsController, /dashboard";
    }

    public function show($id)
    {
        // Concatenamos el ID para que puedas ver que funciona el parámetro dinámico
        return "Hola, HabitsController, /habito/{$id}";
    }

    public function create()
    {
        return "Hola, HabitsController, /habito/crear";
    }

    public function edit()
    {
        return "Hola, HabitsController, /habito/editar";
    }

    public function delete()
    {
        return "Hola, HabitsController, /habito/eliminar";
    }
}