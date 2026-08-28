<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreferenciasUsuario extends Model
{
    public $table = "preferencias_usuario";

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, "Usuario_ID", "ID_Usuario");
    }
}
