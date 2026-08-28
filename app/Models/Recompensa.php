<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Recompensa extends Model
{
    public $table = "recompensa";

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, "Usuario_ID", "ID_Usuario");
    }

    public function metas(): BelongsToMany {
        return $this->belongsToMany(
            Meta::class,
            "recompensas_meta",
            "Recompensa_ID",
            "Habito_ID"
        )
    }
}
