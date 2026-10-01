<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingsController extends Controller
{
    public function settings()
    {
        return view('settings.settings');
    }

    public function updatePreferences(Request $request)
    {
        $data = $request->validate([
            'proverbios' => ['required', 'boolean'],
            'frases' => ['required', 'boolean'],
        ]);

        $contenido = match (true) {
            $data['proverbios'] && $data['frases'] => 'Ambos',
            (bool) $data['proverbios'] => 'Proverbios',
            (bool) $data['frases'] => 'Frases',
            default => 'Ninguno',
        };

        $request->user()->updatePreferences(['ContenidoMotivacional' => $contenido]);

        return response()->json(['contenido' => $contenido]);
    }
}
