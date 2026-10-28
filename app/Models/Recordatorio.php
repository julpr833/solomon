<?php

namespace App\Models;

use App\Enums\ReminderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $ID_Recordatorio
 * @property int $Habito_ID
 * @property Carbon $ProximaFecha
 * @property string|null $Mensaje
 * @property ReminderStatus $Estado
 * @property Carbon|null $EnviadoEn
 * @property int $Intentos
 * @property string|null $UltimoError
 */
class Recordatorio extends Model
{
    protected $table = 'recordatorio';

    protected $primaryKey = 'ID_Recordatorio';

    protected $fillable = [
        'Habito_ID',
        'ProximaFecha',
        'Mensaje',
        'Estado',
        'EnviadoEn',
        'Intentos',
        'UltimoError',
    ];

    protected function casts(): array
    {
        return [
            'ProximaFecha' => 'datetime',
            'EnviadoEn' => 'datetime',
            'Intentos' => 'integer',
            'Estado' => ReminderStatus::class,
        ];
    }

    /** @return BelongsTo<Habito, $this> */
    public function habito(): BelongsTo
    {
        return $this->belongsTo(Habito::class, 'Habito_ID', 'ID_Habito');
    }

    /**
     * @param  Builder<Recordatorio>  $query
     * @return Builder<Recordatorio>
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('Estado', ReminderStatus::Pending->value);
    }

    /**
     * @param  Builder<Recordatorio>  $query
     * @return Builder<Recordatorio>
     */
    public function scopeDue(Builder $query): Builder
    {
        return $query->pending()->where('ProximaFecha', '<=', now());
    }
}
