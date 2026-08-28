<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CumplimientoHabito extends Model
{
    public $table = "cumplimiento_habito";

    public function habito(): BelongsTo
    {
        return $this->belongsTo(Habito::class, "Habito_ID", "ID_Habito");
    }
}
