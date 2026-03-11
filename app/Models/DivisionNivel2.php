<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int         $id_nivel2
 * @property int         $id_nivel1
 * @property string      $nombre
 * @property string|null $codigo
 * @property string|null $tipo      Municipio, Ciudad, Cantón…
 * @property string|null $capital
 * @property int|null    $poblacion
 * @property bool        $activo
 * @property string      $creado_en
 */
class DivisionNivel2 extends BaseModel
{
    protected $table      = 'divisiones_nivel2';
    protected $primaryKey = 'id_nivel2';

    /** sin actualizado_en en esta tabla */
    public $timestamps = false;
    const  CREATED_AT  = 'creado_en';
    const  UPDATED_AT  = null;

    protected $fillable = [
        'id_nivel1',
        'nombre',
        'codigo',
        'tipo',
        'capital',
        'poblacion',
        'activo',
    ];

    protected $casts = [
        'activo'    => 'boolean',
        'poblacion' => 'integer',
    ];

    // ── Relaciones ────────────────────────────────────────────────

    public function nivel1(): BelongsTo
    {
        return $this->belongsTo(DivisionNivel1::class, 'id_nivel1', 'id_nivel1');
    }

    public function divisionesNivel3(): HasMany
    {
        return $this->hasMany(DivisionNivel3::class, 'id_nivel2', 'id_nivel2');
    }

    public function usuarios(): HasMany
    {
        return $this->hasMany(Usuario::class, 'id_nivel2', 'id_nivel2');
    }

    public function contactos(): HasMany
    {
        return $this->hasMany(Contacto::class, 'id_nivel2', 'id_nivel2');
    }

    public function direccionesContacto(): HasMany
    {
        return $this->hasMany(DireccionContacto::class, 'id_nivel2', 'id_nivel2');
    }

    // ── Scopes propios ────────────────────────────────────────────

    /** Filtra por nivel1 (departamento/estado) */
    public function scopeDeNivel1(Builder $query, int $idNivel1): Builder
    {
        return $query->where('id_nivel1', $idNivel1);
    }

    /** Ordena alfabéticamente */
    public function scopeAlfabetico(Builder $query): Builder
    {
        return $query->orderBy('nombre');
    }
}