<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int         $id_profesion
 * @property string      $nombre
 * @property string|null $descripcion
 * @property string|null $categoria
 * @property bool        $activo
 * @property string      $creado_en
 */
class Profesion extends BaseModel
{
    protected $table      = 'profesiones';
    protected $primaryKey = 'id_profesion';

    /** sin actualizado_en en esta tabla */
    public $timestamps = false;
    const  CREATED_AT  = 'creado_en';
    const  UPDATED_AT  = null;

    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // ── Relaciones ────────────────────────────────────────────────

    public function usuarios(): HasMany
    {
        return $this->hasMany(Usuario::class, 'id_profesion', 'id_profesion');
    }

    public function contactos(): HasMany
    {
        return $this->hasMany(Contacto::class, 'id_profesion', 'id_profesion');
    }

    // ── Scopes propios ────────────────────────────────────────────

    /** Filtra por categoría (Salud, Tecnología, Educación…) */
    public function scopeCategoria(Builder $query, string $categoria): Builder
    {
        return $query->where('categoria', $categoria);
    }

    /** Ordena alfabéticamente por nombre */
    public function scopeAlfabetico(Builder $query): Builder
    {
        return $query->orderBy('nombre');
    }
}