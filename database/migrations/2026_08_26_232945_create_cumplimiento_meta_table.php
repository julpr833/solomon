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
        Schema::create("cumplimiento_meta", function (Blueprint $table) {
            $table
                ->foreignId("Habito_ID")
                ->constrained("habito", "ID_Habito")
                ->onDelete("cascade");
            $table->smallInteger("RachaRequerida");
            $table
                ->foreign("RachaRequerida")
                ->references("RachaRequerida")
                ->on("meta");
            $table->timestamp("MarcaCumplimiento")->primary()->useCurrent();
            $table->smallInteger("RachaAlCumplir");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("cumplimiento_meta");
    }
};
