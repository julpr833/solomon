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
        Schema::create("usuario", function (Blueprint $table) {
            $table->id("ID_Usuario");
            $table->string("NombreUsuario", 25)->unique();
            $table->string("Correo", 255)->unique();
            $table->string("Contrasenia_Hash", 255);
            $table->timestamp("email_verified_at")->nullable();
            $table->timestamp("FechaRegistro")->useCurrent();
            $table->string("Avatar_URL", 120);
            $table->string("Biografia", 300)->nullable();
            $table->enum("Sexo", ["M", "F"]);
            $table->string("telefono", 15)->unique()->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create("password_reset_tokens", function (Blueprint $table) {
            $table->string("email")->primary();
            $table->string("token");
            $table->timestamp("created_at")->nullable();
        });

        Schema::create("sessions", function (Blueprint $table) {
            $table->string("id")->primary();
            $table->foreignId("user_id")->nullable()->index();
            $table->string("ip_address", 45)->nullable();
            $table->text("user_agent")->nullable();
            $table->longText("payload");
            $table->integer("last_activity")->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("usuario");
        Schema::dropIfExists("password_reset_tokens");
        Schema::dropIfExists("sessions");
    }
};
