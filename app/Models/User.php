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
use Illuminate\Support\Facades\DB;

#[Fillable(["NombreUsuario", "email", "password", "Avatar_URL", "Sexo", "telefono"])]
#[Hidden(["password", "remember_token"])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    public $table = "users";
    protected $primaryKey = 'ID_Usuario';

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

    public function getMaxRacha(): int
    {
        return DB::table('cumplimiento_habito as ch')
            ->join('habito as h', 'h.ID_Habito', '=', 'ch.Habito_ID')
            ->where('h.Usuario_ID', $this->ID_Usuario)
            ->max('ch.RachaAlCumplir') ?? 0;
    }

    public function getCurrentRacha(): int
    {
        $lastCompletion = DB::table('cumplimiento_habito as ch')
            ->join('habito as h', 'h.ID_Habito', '=', 'ch.Habito_ID')
            ->where('h.Usuario_ID', $this->ID_Usuario)
            ->select('ch.RachaAlCumplir', 'ch.MarcaCumplimiento')
            ->orderByDesc('ch.MarcaCumplimiento')
            ->first();

        $lastFailure = DB::table('fallo_habito as f')
            ->join('habito as h', 'h.ID_Habito', '=', 'f.Habito_ID')
            ->where('h.Usuario_ID', $this->ID_Usuario)
            ->select('f.MarcaFallo')
            ->orderByDesc('f.MarcaFallo')
            ->first();

        if (!$lastCompletion) {
            return 0;
        }

        if ($lastFailure && $lastFailure->MarcaFallo > $lastCompletion->MarcaCumplimiento) {
            return 0;
        }

        return (int) $lastCompletion->RachaAlCumplir;
    }
}
