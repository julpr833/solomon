<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Habito extends Model
{
    public $table = "habito";
    protected $primaryKey = 'ID_Habito';

    public function recordatorios(): HasMany
    {
        return $this->hasMany(Recordatorio::class, "ID_Habito", "ID_Habito");
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, "ID_Usuario", "Usuario_ID");
    }

    public function metas(): HasMany
    {
        return $this->hasMany(Meta::class, "Habito_ID", "ID_Habito");
    }

    public function cumplimientos(): HasMany
    {
        return $this->hasMany(
            CumplimientoHabito::class,
            "Habito_ID",
            "ID_Habito",
        );
    }

    public function fallos(): HasMany
    {
        return $this->hasMany(FalloHabito::class, "Habito_ID", "ID_Habito");
    }

    public function getCurrentRacha(): int
    {
        $lastCompletion = DB::table('cumplimiento_habito')
            ->where('Habito_ID', $this->ID_Habito)
            ->select('RachaAlCumplir', 'MarcaCumplimiento')
            ->orderByDesc('MarcaCumplimiento')
            ->first();

        if (!$lastCompletion) {
            return 0;
        }

        $lastFailure = DB::table('fallo_habito')
            ->where('Habito_ID', $this->ID_Habito)
            ->max('MarcaFallo');

        if ($lastFailure && $lastFailure > $lastCompletion->MarcaCumplimiento) {
            return 0;
        }

        return (int) $lastCompletion->RachaAlCumplir;
    }
}
