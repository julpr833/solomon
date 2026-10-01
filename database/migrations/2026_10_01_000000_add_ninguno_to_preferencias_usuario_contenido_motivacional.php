<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::table('preferencias_usuario')
            ->where('ContenidoMotivacional', 'Ninguno')
            ->update(['ContenidoMotivacional' => 'Ambos']);

        Schema::table('preferencias_usuario', function (Blueprint $table) {
            $table
                ->enum('ContenidoMotivacional', ['Proverbios', 'Frases', 'Ambos', 'Ninguno'])
                ->default('Ambos')
                ->change();
        });
    }

    public function down(): void
    {
        DB::table('preferencias_usuario')
            ->where('ContenidoMotivacional', 'Ninguno')
            ->update(['ContenidoMotivacional' => 'Ambos']);

        Schema::table('preferencias_usuario', function (Blueprint $table) {
            $table
                ->enum('ContenidoMotivacional', ['Proverbios', 'Frases', 'Ambos'])
                ->default('Ambos')
                ->change();
        });
    }
};
