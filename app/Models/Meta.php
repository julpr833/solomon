<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Meta extends Model
{
    public $table = "meta";

    public function recompensas(): BelongsToMany
    {
        return $this->belongsToMany(
            Recompensa::class,
            "RECOMPENSAS_META",
            "Habito_ID",
            "Recompensa_ID",
        );
    }

    public function habito(): BelongsTo
    {
        return $this->belongsTo(Habito::class, "ID_Habito", "Habito_ID");
    }
}
