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
        Schema::create("meta", function (Blueprint $table) {
            $table
                ->foreignId("Habito_ID")
                ->constrained("habito", "ID_Habito")
                ->onDelete("cascade");
            $table->smallInteger("RachaRequerida")->primary();
            $table->boolean("Repetible")->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("meta");
    }
};
