<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CumplimientoHabito extends Model
{
    public $table = 'cumplimiento_habito';

    protected $primaryKey = 'MarcaCumplimiento';

    public $incrementing = false;

    protected $fillable = ['Habito_ID', 'MarcaCumplimiento', 'RachaAlCumplir'];

    /** @return BelongsTo<Habito, $this> */
    public function habito(): BelongsTo
    {
        return $this->belongsTo(Habito::class, 'Habito_ID', 'ID_Habito');
    }
}
