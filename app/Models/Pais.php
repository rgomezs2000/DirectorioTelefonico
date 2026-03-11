<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int         $id_pais
 * @property string      $nombre
 * @property string|null $nombre_oficial
 * @property string      $iso2
 * @property string      $iso3
 * @property string|null $codigo_numerico
 * @property string|null $codigo_telefono
 * @property string|null $continente
 * @property string|null $capital
 * @property string|null $moneda
 * @property string|null $idioma_oficial
 * @property bool        $activo
 * @property string      $creado_en
 */
class Pais extends BaseModel
{
    protected $table      = 'paises';
    protected $primaryKey = 'id_pais';

    /** sin actualizado_en en esta tabla */
    public $timestamps = false;
    const  CREATED_AT  = 'creado_en';
    const  UPDATED_AT  = null;

    protected $fillable = [
        'nombre',
        'nombre_oficial',
        'iso2',
        'iso3',
        'codigo_numerico',
        'codigo_telefono',
        'continente',
        'capital',
        'moneda',
        'idioma_oficial',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // ── Relaciones ────────────────────────────────────────────────

    public function divisionesNivel1(): HasMany
    {
        return $this->hasMany(DivisionNivel1::class, 'id_pais', 'id_pais');
    }

    public function usuarios(): HasMany
    {
        return $this->hasMany(Usuario::class, 'id_pais', 'id_pais');
    }

    public function contactos(): HasMany
    {
        return $this->hasMany(Contacto::class, 'id_pais', 'id_pais');
    }

    public function telefonosContacto(): HasMany
    {
        return $this->hasMany(TelefonoContacto::class, 'id_pais', 'id_pais');
    }

    public function direccionesContacto(): HasMany
    {
        return $this->hasMany(DireccionContacto::class, 'id_pais', 'id_pais');
    }

    // ── Scopes propios ────────────────────────────────────────────

    /** Filtra por continente */
    public function scopeContinente(Builder $query, string $continente): Builder
    {
        return $query->where('continente', $continente);
    }

    /** Busca por código ISO2 */
    public function scopeIso2(Builder $query, string $iso2): Builder
    {
        return $query->where('iso2', strtoupper($iso2));
    }
}