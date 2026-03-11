<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int         $id_nivel1
 * @property int         $id_pais
 * @property string      $nombre
 * @property string|null $codigo
 * @property string|null $tipo      Estado, Departamento, Provincia…
 * @property string|null $capital
 * @property bool        $activo
 * @property string      $creado_en
 */
class DivisionNivel1 extends BaseModel
{
    protected $table      = 'divisiones_nivel1';
    protected $primaryKey = 'id_nivel1';

    /** sin actualizado_en en esta tabla */
    public $timestamps = false;
    const  CREATED_AT  = 'creado_en';
    const  UPDATED_AT  = null;

    protected $fillable = [
        'id_pais',
        'nombre',
        'codigo',
        'tipo',
        'capital',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // ── Relaciones ────────────────────────────────────────────────

    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class, 'id_pais', 'id_pais');
    }

    public function divisionesNivel2(): HasMany
    {
        return $this->hasMany(DivisionNivel2::class, 'id_nivel1', 'id_nivel1');
    }

    public function usuarios(): HasMany
    {
        return $this->hasMany(Usuario::class, 'id_nivel1', 'id_nivel1');
    }

    public function contactos(): HasMany
    {
        return $this->hasMany(Contacto::class, 'id_nivel1', 'id_nivel1');
    }

    public function direccionesContacto(): HasMany
    {
        return $this->hasMany(DireccionContacto::class, 'id_nivel1', 'id_nivel1');
    }

    // ── Scopes propios ────────────────────────────────────────────

    /** Filtra por país */
    public function scopeDePais(Builder $query, int $idPais): Builder
    {
        return $query->where('id_pais', $idPais);
    }

    /** Ordena alfabéticamente */
    public function scopeAlfabetico(Builder $query): Builder
    {
        return $query->orderBy('nombre');
    }
}