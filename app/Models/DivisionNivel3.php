<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int         $id_nivel3
 * @property int         $id_nivel2
 * @property string      $nombre
 * @property string|null $codigo
 * @property string|null $tipo          Barrio, Vereda, Parroquia, Localidad…
 * @property string|null $codigo_postal
 * @property bool        $activo
 * @property string      $creado_en
 */
class DivisionNivel3 extends BaseModel
{
    protected $table      = 'divisiones_nivel3';
    protected $primaryKey = 'id_nivel3';

    /** sin actualizado_en en esta tabla */
    public $timestamps = false;
    const  CREATED_AT  = 'creado_en';
    const  UPDATED_AT  = null;

    protected $fillable = [
        'id_nivel2',
        'nombre',
        'codigo',
        'tipo',
        'codigo_postal',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // ── Relaciones ────────────────────────────────────────────────

    public function nivel2(): BelongsTo
    {
        return $this->belongsTo(DivisionNivel2::class, 'id_nivel2', 'id_nivel2');
    }

    public function usuarios(): HasMany
    {
        return $this->hasMany(Usuario::class, 'id_nivel3', 'id_nivel3');
    }

    public function contactos(): HasMany
    {
        return $this->hasMany(Contacto::class, 'id_nivel3', 'id_nivel3');
    }

    public function direccionesContacto(): HasMany
    {
        return $this->hasMany(DireccionContacto::class, 'id_nivel3', 'id_nivel3');
    }

    // ── Scopes propios ────────────────────────────────────────────

    /** Filtra por nivel2 (municipio/ciudad) */
    public function scopeDeNivel2(Builder $query, int $idNivel2): Builder
    {
        return $query->where('id_nivel2', $idNivel2);
    }

    /** Filtra por código postal */
    public function scopeCodigoPostal(Builder $query, string $cp): Builder
    {
        return $query->where('codigo_postal', $cp);
    }

    /** Ordena alfabéticamente */
    public function scopeAlfabetico(Builder $query): Builder
    {
        return $query->orderBy('nombre');
    }
}