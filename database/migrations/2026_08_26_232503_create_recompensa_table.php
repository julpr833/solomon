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
        Schema::create("recompensa", function (Blueprint $table) {
            $table->id("ID_Recompensa");
            $table
                ->foreignId("Usuario_ID")
                ->constrained("usuario", "ID_Usuario")
                ->onDelete("cascade");
            $table->string("Nombre", 45);
            $table->string("Descripcion", 150)->nullable();
            $table->string("Emoji", 42)->default("🏆");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("recompensa");
    }
};
