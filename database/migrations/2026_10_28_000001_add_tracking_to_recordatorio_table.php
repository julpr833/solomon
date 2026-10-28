<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $legacySchedule = Schema::hasColumn('recordatorio', 'Horario')
            && ! Schema::hasColumn('recordatorio', 'ProximaFecha');

        if ($legacySchedule) {
            Schema::table('recordatorio', function (Blueprint $table) {
                $table->timestamp('ProximaFecha')->nullable();
            });

            DB::table('recordatorio')
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
        }

        Schema::table('recordatorio', function (Blueprint $table) {
            if (! Schema::hasColumn('recordatorio', 'Estado')) {
                $table->string('Estado', 20)->default('pendiente');
            }

            if (! Schema::hasColumn('recordatorio', 'EnviadoEn')) {
                $table->timestamp('EnviadoEn')->nullable();
            }

            if (! Schema::hasColumn('recordatorio', 'Intentos')) {
                $table->unsignedTinyInteger('Intentos')->default(0);
            }

            if (! Schema::hasColumn('recordatorio', 'UltimoError')) {
                $table->text('UltimoError')->nullable();
            }
        });

        if (! Schema::hasIndex('recordatorio', ['Habito_ID'], 'unique')) {
            Schema::table('recordatorio', function (Blueprint $table) {
                $table->unique('Habito_ID');
            });
        }

        if (! Schema::hasIndex('recordatorio', ['Estado', 'ProximaFecha'])) {
            Schema::table('recordatorio', function (Blueprint $table) {
                $table->index(['Estado', 'ProximaFecha']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasIndex('recordatorio', ['Habito_ID'], 'unique')) {
            Schema::table('recordatorio', function (Blueprint $table) {
                $table->dropUnique(['Habito_ID']);
            });
        }

        if (Schema::hasIndex('recordatorio', ['Estado', 'ProximaFecha'])) {
            Schema::table('recordatorio', function (Blueprint $table) {
                $table->dropIndex(['Estado', 'ProximaFecha']);
            });
        }

        Schema::table('recordatorio', function (Blueprint $table) {
            foreach (['Estado', 'EnviadoEn', 'Intentos', 'UltimoError'] as $column) {
                if (Schema::hasColumn('recordatorio', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
