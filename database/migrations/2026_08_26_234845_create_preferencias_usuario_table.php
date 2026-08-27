<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("preferencias_usuario", function (Blueprint $table) {
            $table
                ->foreignId("Usuario_ID")
                ->constrained("usuario", "ID_Usuario")
                ->onDelete("cascade");
            $table
                ->enum("ContenidoMotivacional", [
                    "Proverbios",
                    "Frases",
                    "Ambos",
                ])
                ->default("Ambos");
            $table->boolean("FelicitarMetas")->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("preferencias_usuario");
    }
};
