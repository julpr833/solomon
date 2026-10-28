<?php

namespace App\Models;

use Database\Factories\MetaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Meta extends Model
{
    /** @use HasFactory<MetaFactory> */
    use HasFactory;

    public $table = 'meta';

    protected $primaryKey = 'RachaRequerida';

    public $incrementing = false;

    protected $fillable = ['Habito_ID', 'RachaRequerida', 'Repetible'];

    protected function casts(): array
    {
        return [
            'Repetible' => 'boolean',
        ];
    }

    /** @return BelongsToMany<Recompensa, $this> */
    public function recompensas(): BelongsToMany
    {
        return $this->belongsToMany(
            Recompensa::class,
            'recompensas_meta',
            'RachaRequerida',
            'Recompensa_ID',
        )->withPivot('Habito_ID')->withTimestamps();
    }

    /** @return BelongsTo<Habito, $this> */
    public function habito(): BelongsTo
    {
        return $this->belongsTo(Habito::class, 'Habito_ID', 'ID_Habito');
    }
}
