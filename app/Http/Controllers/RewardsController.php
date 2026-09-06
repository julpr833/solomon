<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RewardsController
{
    public function create()
    {
        return "Hola, RewardsController, /recompensas/crear";
    }

    public function edit()
    {
        return "Hola, RewardsController, /recompensas/editar";
    }

    public function delete()
    {
        return "Hola, RewardsController, /recompensas/eliminar";
    }
}