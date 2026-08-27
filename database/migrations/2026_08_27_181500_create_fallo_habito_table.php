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
        Schema::create("fallo_habito", function (Blueprint $table) {
            $table
                ->foreignId("Habito_ID")
                ->constrained("habito", "ID_Habito")
                ->onDelete("cascade");
            $table->timestamp("MarcaFallo")->primary()->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("fallo_habito");
    }
};
