<?php

namespace App\Http\Controllers;

use App\Models\Habito;
use App\Models\Meta;
use App\Models\Recompensa;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class GoalsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $habitos = $user->habitos()
            ->select(['ID_Habito', 'Objetivo'])
            ->orderBy('Objetivo')
            ->get();
        $recompensas = $user->recompensas()
            ->select(['ID_Recompensa', 'Nombre', 'Descripcion', 'Emoji'])
            ->orderBy('Nombre')
            ->get();

        $metas = Meta::query()
            ->select(['Habito_ID', 'RachaRequerida', 'Repetible'])
            ->whereIn('Habito_ID', $habitos->pluck('ID_Habito'))
            ->with([
                'habito' => fn ($query) => $query
                    ->select(['habito.ID_Habito', 'habito.Objetivo'])
                    ->withProgress(),
                'recompensas' => fn ($query) => $query
                    ->select(['recompensa.ID_Recompensa', 'recompensa.Nombre', 'recompensa.Emoji']),
            ])
            ->orderBy('RachaRequerida')
            ->get();

        return view('goals.index', compact('metas', 'habitos', 'recompensas'));
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'habito_id' => ['required', 'integer'],
            'racha_requerida' => ['required', 'integer', 'min:1', 'max:365'],
            'repetible' => ['required', 'boolean'],
            'recompensa_id' => ['nullable', 'integer'],
        ]);

        $habitoId = Habito::where('Usuario_ID', $request->user()->getKey())
            ->whereKey($data['habito_id'])
            ->value('ID_Habito');

        if (! $habitoId) {
            return back()->withErrors(['habito_id' => 'El hábito seleccionado no es válido.'])->withInput();
        }

        if (Meta::where('RachaRequerida', $data['racha_requerida'])->exists()) {
            return back()->withErrors(['racha_requerida' => 'Ya existe una meta con esa cantidad de días.'])->withInput();
        }

        DB::transaction(function () use ($request, $habitoId, $data) {
            $meta = Meta::create([
                'Habito_ID' => $habitoId,
                'RachaRequerida' => $data['racha_requerida'],
                'Repetible' => $data['repetible'],
            ]);

            $recompensaId = $this->ownedRecompensaId($request, $data['recompensa_id'] ?? null);

            if ($recompensaId) {
                $meta->recompensas()->attach(
                    $recompensaId,
                    ['Habito_ID' => $meta->Habito_ID],
                );
            }
        });

        return redirect()->route('goals')->with('status', 'Meta creada con éxito.');
    }

    public function edit(Request $request)
    {
        $data = $request->validate([
            'habito_id' => ['required', 'integer'],
            'racha_requerida' => ['required', 'integer', 'min:1', 'max:365'],
            'repetible' => ['required', 'boolean'],
            'recompensa_id' => ['nullable', 'integer'],
        ]);

        $meta = $this->ownedMeta($request, $data['habito_id'], $data['racha_requerida']);

        if (! $meta) {
            return back()->withErrors(['meta' => 'La meta seleccionada no es válida.']);
        }

        DB::transaction(function () use ($request, $meta, $data) {
            $meta->update(['Repetible' => $data['repetible']]);

            $recompensaId = $this->ownedRecompensaId($request, $data['recompensa_id'] ?? null);

            $meta->recompensas()->sync(
                $recompensaId ? [$recompensaId => ['Habito_ID' => $meta->Habito_ID]] : [],
            );
        });

        return redirect()->route('goals')->with('status', 'Meta actualizada con éxito.');
    }

    public function delete(Request $request)
    {
        $data = $request->validate([
            'habito_id' => ['required', 'integer'],
            'racha_requerida' => ['required', 'integer'],
        ]);

        $meta = $this->ownedMeta($request, $data['habito_id'], $data['racha_requerida']);

        if (! $meta) {
            return back()->withErrors(['meta' => 'La meta seleccionada no es válida.']);
        }

        DB::transaction(function () use ($meta) {
            $meta->recompensas()->detach();

            DB::table('cumplimiento_meta')
                ->where('Habito_ID', $meta->Habito_ID)
                ->where('RachaRequerida', $meta->RachaRequerida)
                ->delete();

            $meta->delete();
        });

        return redirect()->route('goals')->with('status', 'Meta eliminada.');
    }

    private function ownedMeta(Request $request, int $habitoId, int $racha): ?Meta
    {
        return Meta::query()
            ->where('Habito_ID', $habitoId)
            ->where('RachaRequerida', $racha)
            ->whereHas('habito', fn ($query) => $query->where('Usuario_ID', $request->user()->getKey()))
            ->first();
    }

    private function ownedRecompensaId(Request $request, ?int $id): ?int
    {
        if (! $id) {
            return null;
        }

        $recompensaId = Recompensa::where('Usuario_ID', $request->user()->getKey())
            ->whereKey($id)
            ->value('ID_Recompensa');

        return $recompensaId === null ? null : (int) $recompensaId;
    }
}
