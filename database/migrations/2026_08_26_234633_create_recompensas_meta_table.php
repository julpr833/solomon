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
        Schema::create("recompensas_meta", function (Blueprint $table) {
            $table
                ->foreignId("Habito_ID")
                ->constrained("habito", "ID_Habito")
                ->onDelete("cascade");
            $table
                ->foreignId("RachaRequerida")
                ->constrained("habito", "RachaRequerida")
                ->onDelete("cascade");
            $table
                ->foreignId("Recompensa_ID")
                ->constrained("recompensa", "ID_Recompensa")
                ->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("recompensas_meta");
    }
};
