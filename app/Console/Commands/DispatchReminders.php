<?php

namespace App\Console\Commands;

use App\Jobs\SendHabitReminder;
use App\Models\Recordatorio;
use Illuminate\Console\Command;

class DispatchReminders extends Command
{
    protected $signature = 'reminders:dispatch';

    protected $description = 'Despacha los recordatorios de WhatsApp vencidos';

    public function handle(): int
    {
        $count = 0;

        Recordatorio::due()
            ->orderBy('ProximaFecha')
            ->chunkById(100, function ($reminders) use (&$count): void {
                foreach ($reminders as $reminder) {
                    SendHabitReminder::dispatch($reminder);
                    $count++;
                }
            }, 'ID_Recordatorio');

        $this->info("Recordatorios despachados: {$count}");

        return self::SUCCESS;
    }
}
