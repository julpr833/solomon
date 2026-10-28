<?php

namespace App\Services;

use App\Enums\ReminderStatus;
use App\Models\Habito;
use Illuminate\Support\Facades\DB;

class HabitService
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Habito $habit, array $attributes): Habito
    {
        $habit->update($attributes);

        return $habit;
    }

    /**
     * @return array{streak: int, already_completed: bool, changed: bool}
     */
    public function complete(Habito $habit): array
    {
        return DB::transaction(function () use ($habit): array {
            if ($habit->isCompletedToday()) {
                return [
                    'streak' => $habit->getCurrentRacha(),
                    'already_completed' => true,
                    'changed' => false,
                ];
            }

            $streak = $habit->getCurrentRacha() + 1;

            $habit->cumplimientos()->create([
                'MarcaCumplimiento' => now(),
                'RachaAlCumplir' => $streak,
            ]);

            return [
                'streak' => $streak,
                'already_completed' => false,
                'changed' => true,
            ];
        });
    }

    /**
     * @return array{streak: int, removed: bool}
     */
    public function uncomplete(Habito $habit): array
    {
        return DB::transaction(function () use ($habit): array {
            $removed = $habit->cumplimientos()
                ->whereDate('MarcaCumplimiento', now()->toDateString())
                ->delete() > 0;

            $remaining = $habit->newQuery()
                ->withProgress()
                ->findOrFail($habit->getKey());

            return [
                'streak' => $remaining->getCurrentRacha(),
                'removed' => $removed,
            ];
        });
    }

    public function syncReminder(Habito $habit, ?string $time): void
    {
        if (! $time) {
            $habit->recordatorio()->delete();

            return;
        }

        $next = now()->setTimeFromTimeString($time);

        if ($next->lessThanOrEqualTo(now())) {
            $next->addDay();
        }

        $habit->recordatorio()->updateOrCreate([], [
            'ProximaFecha' => $next,
            'Mensaje' => 'Recordatorio: '.$habit->Objetivo,
            'Estado' => ReminderStatus::Pending,
            'Intentos' => 0,
            'UltimoError' => null,
        ]);
    }

    public function delete(Habito $habit): void
    {
        DB::transaction(function () use ($habit): void {
            DB::table('recompensas_meta')
                ->whereIn('RachaRequerida', $habit->metas()->select('RachaRequerida'))
                ->delete();

            $habit->cumplimientosMeta()->delete();
            $habit->metas()->delete();
            $habit->cumplimientos()->delete();
            $habit->fallos()->delete();
            $habit->recordatorios()->delete();

            $habit->delete();
        });
    }
}
