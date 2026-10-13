<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

#[Fillable(['NombreUsuario', 'email', 'password', 'Avatar_URL', 'Sexo', 'telefono'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'ID_Usuario';

    public function habitos(): HasMany
    {
        return $this->hasMany(Habito::class, 'Usuario_ID', 'ID_Usuario');
    }

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

    public function getCurrentRacha(): int
    {
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

    public function getTodaysCompletedHabits(): int
    {
        return DB::table('cumplimiento_habito as ch')
            ->join('habito as h', 'h.ID_Habito', '=', 'ch.Habito_ID')
            ->where('h.Usuario_ID', $this->ID_Usuario)
            ->whereDate('ch.MarcaCumplimiento', now()->toDateString())
            ->distinct()
            ->count('ch.Habito_ID');
    }

    public function getTodaysHabitCount(): int
    {
        return $this->habitos()
            ->leftJoin('cumplimiento_habito as ch', function ($join) {
                $join->on('habito.ID_Habito', '=', 'ch.Habito_ID')
                    ->whereRaw('ch.MarcaCumplimiento = (
                     SELECT MAX(sub.MarcaCumplimiento) 
                     FROM cumplimiento_habito sub 
                     WHERE sub.Habito_ID = habito.ID_Habito
                 )');
            })
            ->where(function ($query) {
                // Case 1: Habit has never been completed
                $query->whereNull('ch.MarcaCumplimiento')
                    // Case 2: Last completion was today
                    ->orWhereDate('ch.MarcaCumplimiento', '=', now())
                    // Case 3: Next due date is today or in the past
                    ->orWhereRaw("
                    CASE habito.Frecuencia
                        WHEN 'Pluridiaria' THEN DATE_ADD(ch.MarcaCumplimiento, INTERVAL 1 DAY)
                        WHEN 'Diaria' THEN DATE_ADD(ch.MarcaCumplimiento, INTERVAL 1 DAY)
                        WHEN 'Semanal' THEN DATE_ADD(ch.MarcaCumplimiento, INTERVAL 1 WEEK)
                        WHEN 'Mensual' THEN DATE_ADD(ch.MarcaCumplimiento, INTERVAL 1 MONTH)
                        WHEN 'Anual' THEN DATE_ADD(ch.MarcaCumplimiento, INTERVAL 1 YEAR)
                    END <= ?", [now()->endOfDay()]);
            })
            ->count('habito.ID_Habito');
    }

    public function getWeekCompletedPercentage(): int
    {
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();

        $completions = DB::table('cumplimiento_habito as ch')
            ->join('habito as h', 'h.ID_Habito', '=', 'ch.Habito_ID')
            ->where('h.Usuario_ID', $this->ID_Usuario)
            ->whereBetween('ch.MarcaCumplimiento', [$weekStart, $weekEnd])
            ->count();

        $fails = DB::table('fallo_habito as f')
            ->join('habito as h', 'h.ID_Habito', '=', 'f.Habito_ID')
            ->where('h.Usuario_ID', $this->ID_Usuario)
            ->whereBetween('f.MarcaFallo', [$weekStart, $weekEnd])
            ->count();

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

    public function getTotalHabitCount(): int
    {
        return $this->habitos()->count();
    }
}
