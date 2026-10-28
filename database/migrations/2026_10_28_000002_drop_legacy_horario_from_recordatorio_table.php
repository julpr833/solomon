<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('recordatorio', 'Horario')) {
            return;
        }

        DB::table('recordatorio')
            ->whereNull('ProximaFecha')
            ->whereNotNull('Horario')
            ->orderBy('ID_Recordatorio')
            ->each(function (object $reminder): void {
                $next = now()->setTimeFromTimeString((string) $reminder->Horario);

                if ($next->lessThanOrEqualTo(now())) {
                    $next->addDay();
                }

                DB::table('recordatorio')
                    ->where('ID_Recordatorio', $reminder->ID_Recordatorio)
                    ->update(['ProximaFecha' => $next]);
            });

        Schema::table('recordatorio', function (Blueprint $table) {
            $table->dropColumn('Horario');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('recordatorio', 'Horario')) {
            return;
        }

        Schema::table('recordatorio', function (Blueprint $table) {
            $table->time('Horario')->nullable()->after('Habito_ID');
        });
    }
};
