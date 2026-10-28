<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

#[Fillable(['NombreUsuario', 'email', 'password', 'Avatar_URL', 'Sexo', 'telefono', 'Biografia'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'ID_Usuario';

    /** @return HasMany<Habito, $this> */
    public function habitos(): HasMany
    {
        return $this->hasMany(Habito::class, 'Usuario_ID', 'ID_Usuario');
    }

    /** @return HasOne<PreferenciasUsuario, $this> */
    public function preferencias(): HasOne
    {
        return $this->hasOne(
            PreferenciasUsuario::class,
            'Usuario_ID',
            'ID_Usuario',
        );
    }

    public function updatePreferences(array $attributes): Model
    {
        return $this->preferencias()->updateOrCreate(
            ['Usuario_ID' => $this->ID_Usuario],
            $attributes,
        );
    }

    public function updateBiography(string $biography): bool
    {
        return $this->update(['Biografia' => $biography]);
    }

    public function getContenidoMotivacional(): string
    {
        return $this->preferencias->ContenidoMotivacional ?? 'Ambos';
    }

    public function wantsProverbios(): bool
    {
        return in_array($this->getContenidoMotivacional(), ['Proverbios', 'Ambos'], true);
    }

    public function wantsFrases(): bool
    {
        return in_array($this->getContenidoMotivacional(), ['Frases', 'Ambos'], true);
    }

    /** @return HasMany<Recompensa, $this> */
    public function recompensas(): HasMany
    {
        return $this->hasMany(Recompensa::class, 'Usuario_ID', 'ID_Usuario');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getMaxRacha(): int
    {
        return DB::table('cumplimiento_habito as ch')
            ->join('habito as h', 'h.ID_Habito', '=', 'ch.Habito_ID')
            ->where('h.Usuario_ID', $this->ID_Usuario)
            ->max('ch.RachaAlCumplir') ?? 0;
    }

    /**
     * @param  Collection<int, Habito>|null  $habitos
     */
    public function getCurrentRacha(?Collection $habitos = null): int
    {
        if ($habitos !== null) {
            $lastCompletion = $habitos
                ->filter(fn (Habito $habito): bool => (bool) $habito->ultimo_cumplimiento)
                ->sortByDesc('ultimo_cumplimiento')
                ->first();

            if (! $lastCompletion) {
                return 0;
            }

            $lastFailure = $habitos->pluck('ultimo_fallo')->filter()->max();

            if ($lastFailure && $lastFailure > $lastCompletion->ultimo_cumplimiento) {
                return 0;
            }

            return (int) ($lastCompletion->ultima_racha ?? 0);
        }

        $lastCompletion = DB::table('cumplimiento_habito as ch')
            ->join('habito as h', 'h.ID_Habito', '=', 'ch.Habito_ID')
            ->where('h.Usuario_ID', $this->ID_Usuario)
            ->select('ch.RachaAlCumplir', 'ch.MarcaCumplimiento')
            ->orderByDesc('ch.MarcaCumplimiento')
            ->first();

        $lastFailure = DB::table('fallo_habito as f')
            ->join('habito as h', 'h.ID_Habito', '=', 'f.Habito_ID')
            ->where('h.Usuario_ID', $this->ID_Usuario)
            ->select('f.MarcaFallo')
            ->orderByDesc('f.MarcaFallo')
            ->first();

        if (! $lastCompletion) {
            return 0;
        }

        if ($lastFailure && $lastFailure->MarcaFallo > $lastCompletion->MarcaCumplimiento) {
            return 0;
        }

        return (int) $lastCompletion->RachaAlCumplir;
    }

    /**
     * @param  Collection<int, Habito>|null  $habitos
     */
    public function getTodaysCompletedHabits(?Collection $habitos = null): int
    {
        if ($habitos !== null) {
            return $habitos->filter(fn (Habito $habito): bool => $habito->isCompletedToday())->count();
        }

        return DB::table('cumplimiento_habito as ch')
            ->join('habito as h', 'h.ID_Habito', '=', 'ch.Habito_ID')
            ->where('h.Usuario_ID', $this->ID_Usuario)
            ->whereDate('ch.MarcaCumplimiento', now()->toDateString())
            ->distinct()
            ->count('ch.Habito_ID');
    }

    /**
     * @param  Collection<int, Habito>|null  $habitos
     */
    public function getTodaysHabitCount(?Collection $habitos = null): int
    {
        $habitos ??= $this->habitos()->withProgress()->get();

        return $habitos->filter(fn (Habito $habito): bool => $habito->isDueToday())->count();
    }

    public function getWeekCompletedPercentage(): int
    {
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();

        $completionsQuery = DB::table('cumplimiento_habito as ch')
            ->join('habito as h', 'h.ID_Habito', '=', 'ch.Habito_ID')
            ->where('h.Usuario_ID', $this->ID_Usuario)
            ->whereBetween('ch.MarcaCumplimiento', [$weekStart, $weekEnd])
            ->selectRaw('COUNT(*)');

        $failsQuery = DB::table('fallo_habito as f')
            ->join('habito as h', 'h.ID_Habito', '=', 'f.Habito_ID')
            ->where('h.Usuario_ID', $this->ID_Usuario)
            ->whereBetween('f.MarcaFallo', [$weekStart, $weekEnd])
            ->selectRaw('COUNT(*)');

        $totals = DB::query()
            ->selectSub($completionsQuery, 'completions')
            ->selectSub($failsQuery, 'fails')
            ->first();

        $completions = (int) $totals->completions;
        $fails = (int) $totals->fails;

        $total = $completions + $fails;

        if ($total === 0) {
            return 0;
        }

        return (int) round(($completions / $total) * 100);
    }

    public function getHeatmapStart(): Carbon
    {
        return now()->copy()->startOfWeek()->subWeeks(11);
    }

    public function getHeatmapData(): array
    {
        $start = $this->getHeatmapStart();
        $end = now();

        $counts = DB::table('cumplimiento_habito as ch')
            ->join('habito as h', 'h.ID_Habito', '=', 'ch.Habito_ID')
            ->where('h.Usuario_ID', $this->ID_Usuario)
            ->where('ch.MarcaCumplimiento', '>=', $start)
            ->selectRaw('DATE(ch.MarcaCumplimiento) as dia, COUNT(DISTINCT ch.Habito_ID) as n')
            ->groupBy('dia')
            ->pluck('n', 'dia')
            ->all();

        $data = [];

        for ($day = $start->copy(); $day->lte($end); $day->addDay()) {
            $data[$day->format('Y-m-d')] = (int) ($counts[$day->format('Y-m-d')] ?? 0);
        }

        return $data;
    }

    /**
     * @param  Collection<int, Habito>|null  $habitos
     */
    public function getTotalHabitCount(?Collection $habitos = null): int
    {
        return $habitos?->count() ?? $this->habitos()->count();
    }

    /**
     * @param  Collection<int, Habito>  $habitos
     * @return array<string, mixed>
     */
    public function getDashboardStats(Collection $habitos): array
    {
        return [
            'today_completed' => $this->getTodaysCompletedHabits($habitos),
            'today_total' => $this->getTodaysHabitCount($habitos),
            'current_streak' => $this->getCurrentRacha($habitos),
            'week_percentage' => $this->getWeekCompletedPercentage(),
            'heatmap_start' => $this->getHeatmapStart(),
            'heatmap_data' => $this->getHeatmapData(),
            'total_habits' => $this->getTotalHabitCount($habitos),
        ];
    }
}
