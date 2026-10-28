<?php

namespace App\Http\Controllers;

use App\Models\Recompensa;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class RewardsController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:45'],
            'descripcion' => ['nullable', 'string', 'max:150'],
            'emoji' => ['nullable', 'string', 'max:42'],
        ]);

        $request->user()->recompensas()->create([
            'Nombre' => $data['nombre'],
            'Descripcion' => $data['descripcion'] ?? null,
            'Emoji' => $data['emoji'] ?: '🏆',
        ]);

        return redirect()->route('goals')->with('status', 'Recompensa creada con éxito.');
    }

    public function edit(Request $request)
    {
        $data = $request->validate([
            'recompensa_id' => ['required', 'integer'],
            'nombre' => ['required', 'string', 'max:45'],
            'descripcion' => ['nullable', 'string', 'max:150'],
            'emoji' => ['nullable', 'string', 'max:42'],
        ]);

        $recompensa = Recompensa::query()
            ->where('Usuario_ID', $request->user()->getKey())
            ->find($data['recompensa_id']);

        if (! $recompensa) {
            return back()->withErrors(['recompensa' => 'La recompensa seleccionada no es válida.']);
        }

        $recompensa->update([
            'Nombre' => $data['nombre'],
            'Descripcion' => $data['descripcion'] ?? null,
            'Emoji' => $data['emoji'] ?: '🏆',
        ]);

        return redirect()->route('goals')->with('status', 'Recompensa actualizada con éxito.');
    }

    public function delete(Request $request)
    {
        $data = $request->validate([
            'recompensa_id' => ['required', 'integer'],
        ]);

        $recompensa = Recompensa::query()
            ->where('Usuario_ID', $request->user()->getKey())
            ->find($data['recompensa_id']);

        if (! $recompensa) {
            return back()->withErrors(['recompensa' => 'La recompensa seleccionada no es válida.']);
        }

        DB::transaction(function () use ($recompensa): void {
            $recompensa->metas()->detach();
            $recompensa->delete();
        });

        return redirect()->route('goals')->with('status', 'Recompensa eliminada.');
    }
}
