<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GoalsController
{
    public function index()
    {
        return "Hola, GoalsController, /metas";
    }

    public function create()
    {
        return "Hola, GoalsController, /metas/crear";
    }

    public function edit()
    {
        return "Hola, GoalsController, /metas/editar";
    }

    public function delete()
    {
        return "Hola, GoalsController, /metas/eliminar";
    }
}