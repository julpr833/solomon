<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(["Usuario_ID", "ContenidoMotivacional", "FelicitarMetas"])]
class PreferenciasUsuario extends Model
{
    public $table = "preferencias_usuario";
    protected $primaryKey = "Usuario_ID";
    public $incrementing = false;

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, "Usuario_ID", "ID_Usuario");
    }
}
