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
        Schema::create("proverbio", function (Blueprint $table) {
            $table->id("ID_Proverbio");
            $table->string("Proverbio", 150)->unique();
            $table->tinyInteger("Capitulo")->unsigned();
            $table->tinyInteger("Versiculo")->unsigned();
            $table->unique(["Capitulo", "Versiculo"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("proverbio");
    }
};
