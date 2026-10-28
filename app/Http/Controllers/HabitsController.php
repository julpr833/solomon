<?php

namespace App\Http\Controllers;

use App\Http\Requests\Habits\DeleteHabitRequest;
use App\Http\Requests\Habits\StoreHabitRequest;
use App\Http\Requests\Habits\UpdateHabitRequest;
use App\Models\FraseMotivacional;
use App\Models\Habito;
use App\Models\Proverbio;
use App\Models\User;
use App\Services\HabitService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class HabitsController extends Controller
{
    public function __construct(private readonly HabitService $habits) {}

    public function dashboard(Request $request): View
    {
        $user = $request->user();
        $habits = $user->habitos()
            ->select(['ID_Habito', 'Objetivo', 'Descripcion', 'Frecuencia', 'Prioridad'])
            ->withProgress()
            ->with('recordatorio')
            ->get();
        $stats = $user->getDashboardStats($habits);
        $motivationEnabled = $user->wantsProverbios() || $user->wantsFrases();
        $motivation = $this->motivationPool($user);

        return view('habits.dashboard', compact('habits', 'stats', 'motivation', 'motivationEnabled'));
    }

    public function show(Request $request, int $id): View
    {
        $habito = $request->user()
            ->habitos()
            ->select(['ID_Habito', 'Objetivo', 'Descripcion', 'Frecuencia', 'Prioridad'])
            ->withProgress()
            ->withCount(['cumplimientos', 'fallos'])
            ->withMax('cumplimientos as max_racha', 'RachaAlCumplir')
            ->with([
                'recordatorio',
                'metas' => fn ($query) => $query->select(['Habito_ID', 'RachaRequerida']),
                'metas.recompensas' => fn ($query) => $query
                    ->select(['recompensa.ID_Recompensa', 'recompensa.Nombre', 'recompensa.Emoji']),
            ])
            ->findOrFail($id);

        $heatmap = $habito->getAnnualHeatmap();

        return view('habits.show', compact('habito', 'heatmap'));
    }

    public function create(StoreHabitRequest $request): RedirectResponse
    {
        $habito = $request->user()->habitos()->create($this->attributes($request));

        if ($request->has('hora_recordatorio')) {
            $this->habits->syncReminder($habito, $request->validated('hora_recordatorio'));
        }

        return redirect()->route('dashboard')->with('status', 'Hábito creado con éxito.');
    }

    public function complete(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $habito = $request->user()
            ->habitos()
            ->select(['ID_Habito'])
            ->withProgress()
            ->find($id);

        if (! $habito) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'El hábito seleccionado no es válido.'], 404);
            }

            return back()->withErrors(['habito_id' => 'El hábito seleccionado no es válido.']);
        }

        $result = $this->habits->complete($habito);

        if ($request->expectsJson()) {
            return response()->json([
                'completed' => true,
                ...$result,
            ]);
        }

        return back()->with('status', 'Hábito completado.');
    }

    public function uncomplete(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $habito = $this->ownedHabit($request, $id);

        if (! $habito) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'El hábito seleccionado no es válido.'], 404);
            }

            return back()->withErrors(['habito_id' => 'El hábito seleccionado no es válido.']);
        }

        $result = $this->habits->uncomplete($habito);

        if ($request->expectsJson()) {
            return response()->json([
                'completed' => false,
                'changed' => $result['removed'],
                ...$result,
            ]);
        }

        return back()->with('status', 'Hábito desmarcado.');
    }

    public function edit(UpdateHabitRequest $request): RedirectResponse
    {
        $habito = $this->ownedHabit($request, (int) $request->validated('habito_id'));

        if (! $habito) {
            return back()->withErrors(['habito_id' => 'El hábito seleccionado no es válido.'])->withInput();
        }

        $this->habits->update($habito, $this->attributes($request));

        if ($request->has('hora_recordatorio')) {
            $this->habits->syncReminder($habito, $request->validated('hora_recordatorio'));
        }

        return redirect()->route('dashboard')->with('status', 'Hábito actualizado con éxito.');
    }

    public function delete(DeleteHabitRequest $request): RedirectResponse
    {
        $habito = $this->ownedHabit($request, (int) $request->validated('habito_id'));

        if (! $habito) {
            return back()->withErrors(['habito_id' => 'El hábito seleccionado no es válido.']);
        }

        $this->habits->delete($habito);

        return redirect()->route('dashboard')->with('status', 'Hábito eliminado.');
    }

    /**
     * @return array<int, array{text: string, ref: string, type: string, icon: string}>
     */
    private function motivationPool(User $user): array
    {
        $pool = [];

        if ($user->wantsProverbios()) {
            $pool = Proverbio::query()
                ->get(['Proverbio', 'Capitulo', 'Versiculo'])
                ->map(fn (Proverbio $proverbio): array => [
                    'text' => $proverbio->Proverbio,
                    'ref' => 'Proverbios '.$proverbio->Capitulo.':'.$proverbio->Versiculo,
                    'type' => 'Proverbio Bíblico',
                    'icon' => 'ti-book',
                ])
                ->all();
        }

        if ($user->wantsFrases()) {
            $frases = FraseMotivacional::query()
                ->get(['Frase', 'Fuente'])
                ->map(fn (FraseMotivacional $frase): array => [
                    'text' => $frase->Frase,
                    'ref' => $frase->Fuente ?? 'Anónimo',
                    'type' => 'Frase Motivacional',
                    'icon' => 'ti-quote',
                ])
                ->all();

            $pool = array_merge($pool, $frases);
        }

        return $pool;
    }

    /**
     * @return array<string, mixed>
     */
    private function attributes(StoreHabitRequest $request): array
    {
        $data = $request->validated();

        return [
            'Objetivo' => $data['objetivo'],
            'Descripcion' => $data['descripcion'] ?? null,
            'Frecuencia' => $data['frecuencia'],
            'Prioridad' => $data['prioridad'],
        ];
    }

    private function ownedHabit(Request $request, int $id): ?Habito
    {
        return Habito::query()
            ->where('Usuario_ID', $request->user()->getKey())
            ->find($id);
    }
}
