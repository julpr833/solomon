<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FalloHabito extends Model
{
    public $table = "fallo_habito";

    public function habito(): BelongsTo
    {
        return $this->belongsTo(Habito::class, "ID_Habito", "Habito_ID");
    }
}
