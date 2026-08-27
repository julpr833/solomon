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
        Schema::create("cumplimiento_habito", function (Blueprint $table) {
            $table
                ->foreignId("Habito_ID")
                ->constrained("ID_Habito", "habito")
                ->onDelete("cascade");
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
        Schema::dropIfExists("cumplimiento_habito");
    }
};
