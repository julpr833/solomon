<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CumplimientoMeta extends Model
{
    public $table = "cumplimiento_meta";

    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        "Habito_ID",
        "RachaRequerida",
        "MarcaCumplimiento",
        "RachaAlCumplir",
    ];

    public function meta(): BelongsTo
    {
        return $this->belongsTo(Meta::class, "Habito_ID", "ID_Habito");
    }
}
