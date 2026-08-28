<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recordatorio extends Model
{
    public $table = "recordatorio";

    public function habito(): BelongsTo
    {
        return $this->belongsTo(Habito::class, "ID_Habito", "ID_Habito");
    }
}
