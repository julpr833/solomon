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
        Schema::create("habito", function (Blueprint $table) {
            $table->id("ID_Habito");
            $table
                ->foreignId("Usuario_ID")
                ->constrained("usuario", "ID_Usuario")
                ->onDelete("cascade");
            $table->string("Objetivo", 45);
            $table->string("Descripcion", 300)->nullable();
            $table->enum("Prioridad", ["Baja", "Media", "Alta"]);
            $table->enum("Frecuencia", [
                "Pluridiaria",
                "Daria",
                "Semanal",
                "Mensual",
                "Anual",
            ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("habito");
    }
};
