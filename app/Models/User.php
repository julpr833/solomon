<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(["name", "email", "password"])]
#[Hidden(["password", "remember_token"])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    public $table = "usuario";

    public function habitos(): HasMany
    {
        return $this->hasMany(Habito::class, "Usuario_ID", "ID_Usuario");
    }

    public function preferencias(): HasOne
    {
        return $this->hasOne(
            PreferenciasUsuario::class,
            "Usuario_ID",
            "ID_Usuario",
        );
    }

    public function recompensas(): HasMany
    {
        return $this->hasMany(Recompensa::class, "Usuario_ID", "ID_Usuario");
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
        ];
    }
}
