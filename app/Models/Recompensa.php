<?php

namespace App\Models;

use Database\Factories\RecompensaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Recompensa extends Model
{
    /** @use HasFactory<RecompensaFactory> */
    use HasFactory;

    protected $table = 'recompensa';

    protected $primaryKey = 'ID_Recompensa';

    protected $fillable = ['Usuario_ID', 'Nombre', 'Descripcion', 'Emoji'];

    /** @return BelongsTo<User, $this> */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'Usuario_ID', 'ID_Usuario');
    }

    /** @return BelongsToMany<Meta, $this> */
    public function metas(): BelongsToMany
    {
        return $this->belongsToMany(
            Meta::class,
            'recompensas_meta',
            'Recompensa_ID',
            'RachaRequerida',
        )->withPivot('Habito_ID')->withTimestamps();
    }
}
