<?php

namespace App\Models;

use Database\Factories\HabitoFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * @property string|null $ultimo_cumplimiento
 * @property int|string|null $ultima_racha
 * @property string|null $ultimo_fallo
 * @property int|bool|null $completado_hoy
 */
class Habito extends Model
{
    /** @use HasFactory<HabitoFactory> */
    use HasFactory;

    public const FREQUENCIES = [
        // 'Pluridiaria',
        'Daria',
        'Semanal',
        'Mensual',
        'Anual',
    ];

    public const PRIORITIES = ['Baja', 'Media', 'Alta'];

    public $table = 'habito';

    protected $primaryKey = 'ID_Habito';

    protected $fillable = ['Usuario_ID', 'Objetivo', 'Descripcion', 'Prioridad', 'Frecuencia'];

    /** @return HasMany<Recordatorio, $this> */
    public function recordatorios(): HasMany
    {
        return $this->hasMany(Recordatorio::class, 'Habito_ID', 'ID_Habito');
    }

    /** @return HasOne<Recordatorio, $this> */
    public function recordatorio(): HasOne
    {
        return $this->hasOne(Recordatorio::class, 'Habito_ID', 'ID_Habito');
    }

    /** @return BelongsTo<User, $this> */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'Usuario_ID', 'ID_Usuario');
    }

    /** @return HasMany<Meta, $this> */
    public function metas(): HasMany
    {
        return $this->hasMany(Meta::class, 'Habito_ID', 'ID_Habito');
    }

    /** @return HasMany<CumplimientoHabito, $this> */
    public function cumplimientos(): HasMany
    {
        return $this->hasMany(
            CumplimientoHabito::class,
            'Habito_ID',
            'ID_Habito',
        );
    }

    /** @return HasMany<CumplimientoMeta, $this> */
    public function cumplimientosMeta(): HasMany
    {
        return $this->hasMany(
            CumplimientoMeta::class,
            'Habito_ID',
            'ID_Habito',
        );
    }

    /**
     * @param  Builder<Habito>  $query
     * @return Builder<Habito>
     */
    public function scopeWithProgress(Builder $query): Builder
    {
        $latestCompletion = DB::table('cumplimiento_habito')
            ->select('MarcaCumplimiento')
            ->whereColumn('Habito_ID', 'habito.ID_Habito')
            ->orderByDesc('MarcaCumplimiento')
            ->limit(1);

        $latestStreak = DB::table('cumplimiento_habito')
            ->select('RachaAlCumplir')
            ->whereColumn('Habito_ID', 'habito.ID_Habito')
            ->orderByDesc('MarcaCumplimiento')
            ->limit(1);

        $latestFailure = DB::table('fallo_habito')
            ->select('MarcaFallo')
            ->whereColumn('Habito_ID', 'habito.ID_Habito')
            ->orderByDesc('MarcaFallo')
            ->limit(1);

        $completedToday = DB::table('cumplimiento_habito')
            ->selectRaw('1')
            ->whereColumn('Habito_ID', 'habito.ID_Habito')
            ->whereDate('MarcaCumplimiento', now()->toDateString())
            ->limit(1);

        if ($query->getQuery()->columns === null) {
            $query->select('habito.*');
        }

        return $query
            ->selectSub($latestCompletion, 'ultimo_cumplimiento')
            ->selectSub($latestStreak, 'ultima_racha')
            ->selectSub($latestFailure, 'ultimo_fallo')
            ->selectSub($completedToday, 'completado_hoy');
    }

    public function isCompletedToday(): bool
    {
        if (array_key_exists('completado_hoy', $this->attributes)) {
            return (bool) $this->completado_hoy;
        }

        return $this->cumplimientos()
            ->whereDate('MarcaCumplimiento', now()->toDateString())
            ->exists();
    }

    public function nextOccurrence(Carbon $from): Carbon
    {
        $next = $from->copy();

        return match ($this->Frecuencia) {
            'Pluridiaria', 'Daria' => $next->addDay(),
            'Semanal' => $next->addWeek(),
            'Mensual' => $next->addMonthNoOverflow(),
            'Anual' => $next->addYearNoOverflow(),
        };
    }

    public function nextReminderDate(Carbon $from): Carbon
    {
        $next = $from->copy();

        do {
            $next = $this->nextOccurrence($next);
        } while ($next->lessThanOrEqualTo(now()));

        return $next;
    }

    public function isDueToday(): bool
    {
        $lastCompletion = $this->ultimo_cumplimiento;

        if (! $lastCompletion) {
            return true;
        }

        $lastCompletion = Carbon::parse($lastCompletion);

        if ($lastCompletion->isToday()) {
            return true;
        }

        $nextDue = match ($this->Frecuencia) {
            'Pluridiaria', 'Daria' => $lastCompletion->copy()->addDay(),
            'Semanal' => $lastCompletion->copy()->addWeek(),
            'Mensual' => $lastCompletion->copy()->addMonth(),
            'Anual' => $lastCompletion->copy()->addYear(),
        };

        return $nextDue->lessThanOrEqualTo(now()->endOfDay());
    }

    /** @return HasMany<FalloHabito, $this> */
    public function fallos(): HasMany
    {
        return $this->hasMany(FalloHabito::class, 'Habito_ID', 'ID_Habito');
    }

    /**
     * @return array{
     *     start: Carbon,
     *     months: array<int, string>,
     *     weeks: array<int, array<int, array{date: Carbon, count: int, future: bool, today: bool}>>
     * }
     */
    public function getAnnualHeatmap(int $weeks = 53): array
    {
        $monthNames = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $start = now()->startOfWeek()->subWeeks($weeks - 1);

        $counts = $this->cumplimientos()
            ->where('MarcaCumplimiento', '>=', $start)
            ->selectRaw('DATE(MarcaCumplimiento) as dia, COUNT(*) as total')
            ->groupBy('dia')
            ->pluck('total', 'dia')
            ->all();

        $months = [];
        $weekDays = [];
        $previousMonth = null;

        for ($week = 0; $week < $weeks; $week++) {
            $monday = $start->copy()->addWeeks($week);
            $month = (int) $monday->month;

            if ($month !== $previousMonth) {
                $months[$week] = $monthNames[$month - 1];
                $previousMonth = $month;
            }

            $days = [];

            for ($day = 0; $day < 7; $day++) {
                $date = $monday->copy()->addDays($day);

                $days[] = [
                    'date' => $date,
                    'count' => (int) ($counts[$date->format('Y-m-d')] ?? 0),
                    'future' => $date->isAfter(now()),
                    'today' => $date->isToday(),
                ];
            }

            $weekDays[$week] = $days;
        }

        return [
            'start' => $start,
            'months' => $months,
            'weeks' => $weekDays,
        ];
    }

    public function getCurrentRacha(): int
    {
        if (array_key_exists('ultimo_cumplimiento', $this->attributes)) {
            if (! $this->ultimo_cumplimiento) {
                return 0;
            }

            if ($this->ultimo_fallo && $this->ultimo_fallo > $this->ultimo_cumplimiento) {
                return 0;
            }

            return (int) ($this->ultima_racha ?? 0);
        }

        $lastCompletion = DB::table('cumplimiento_habito')
            ->where('Habito_ID', $this->ID_Habito)
            ->select('RachaAlCumplir', 'MarcaCumplimiento')
            ->orderByDesc('MarcaCumplimiento')
            ->first();

        if (! $lastCompletion) {
            return 0;
        }

        $lastFailure = DB::table('fallo_habito')
            ->where('Habito_ID', $this->ID_Habito)
            ->max('MarcaFallo');

        if ($lastFailure && $lastFailure > $lastCompletion->MarcaCumplimiento) {
            return 0;
        }

        return (int) $lastCompletion->RachaAlCumplir;
    }
}
