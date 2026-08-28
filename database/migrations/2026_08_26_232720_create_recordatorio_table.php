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
        Schema::create("recordatorio", function (Blueprint $table) {
            $table->id("ID_Recordatorio");
            $table
                ->foreignId("Habito_ID")
                ->constrained("habito", "ID_Habito")
                ->onDelete("cascade");
            $table->time("Horario");
            $table->string("Mensaje", 300)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("recordatorio");
    }
};
