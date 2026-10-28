<?php

namespace App\Jobs;

use App\Contracts\WhatsAppGateway;
use App\Enums\ReminderStatus;
use App\Models\Recordatorio;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Throwable;

class SendHabitReminder implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    /** @var array<int, int> */
    public array $backoff = [60, 300, 900];

    public int $uniqueFor = 3600;

    public function __construct(public Recordatorio $recordatorio) {}

    public function handle(WhatsAppGateway $gateway): void
    {
        $reminder = Recordatorio::query()
            ->with(['habito.usuario'])
            ->find($this->recordatorio->getKey());

        if (! $reminder || $reminder->Estado !== ReminderStatus::Pending) {
            return;
        }

        if ($reminder->ProximaFecha->isFuture()) {
            return;
        }

        $habit = $reminder->habito;
        $user = $habit?->usuario;

        if (! $habit || ! $user || ! $user->telefono) {
            $reminder->update([
                'Estado' => ReminderStatus::Failed,
                'UltimoError' => 'El usuario no tiene un teléfono configurado.',
            ]);

            return;
        }

        try {
            $gateway->send($user->telefono, $this->message($reminder));
        } catch (Throwable $exception) {
            $reminder->increment('Intentos');
            $reminder->update(['UltimoError' => Str::limit($exception->getMessage(), 1000)]);

            throw $exception;
        }

        $reminder->update([
            'ProximaFecha' => $habit->nextReminderDate($reminder->ProximaFecha),
            'Estado' => ReminderStatus::Pending,
            'EnviadoEn' => now(),
            'Intentos' => 0,
            'UltimoError' => null,
        ]);
    }

    public function failed(Throwable $exception): void
    {
        $this->recordatorio->update([
            'Estado' => ReminderStatus::Failed,
            'UltimoError' => Str::limit($exception->getMessage(), 1000),
        ]);
    }

    public function uniqueId(): string
    {
        return (string) $this->recordatorio->getKey();
    }

    private function message(Recordatorio $reminder): string
    {
        return $reminder->Mensaje ?: '⏰ Recordatorio: '.$reminder->habito->Objetivo;
    }
}
