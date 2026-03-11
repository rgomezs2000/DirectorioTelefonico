<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int         $id_tipo_usuario
 * @property string      $nombre
 * @property string|null $descripcion
 * @property int         $nivel_acceso
 * @property bool        $activo
 * @property string      $creado_en
 */
class TipoUsuario extends BaseModel
{
    protected $table      = 'tipos_usuario';
    protected $primaryKey = 'id_tipo_usuario';

    /** sin actualizado_en en esta tabla */
    public $timestamps = false;
    const  CREATED_AT  = 'creado_en';
    const  UPDATED_AT  = null;

    protected $fillable = [
        'nombre',
        'descripcion',
        'nivel_acceso',
        'activo',
    ];

    protected $casts = [
        'activo'       => 'boolean',
        'nivel_acceso' => 'integer',
    ];

    // ── Relaciones ────────────────────────────────────────────────

    public function usuarios(): HasMany
    {
        return $this->hasMany(Usuario::class, 'id_tipo_usuario', 'id_tipo_usuario');
    }

    public function permisos(): HasMany
    {
        return $this->hasMany(Permiso::class, 'id_tipo_usuario', 'id_tipo_usuario');
    }

    // ── Scopes propios ────────────────────────────────────────────

    /** Filtra por nivel de acceso mínimo */
    public function scopeNivelMinimo(Builder $query, int $nivel): Builder
    {
        return $query->where('nivel_acceso', '>=', $nivel);
    }

    /** Ordena por nivel de acceso de mayor a menor */
    public function scopePorNivel(Builder $query): Builder
    {
        return $query->orderByDesc('nivel_acceso');
    }

    // ── Helpers ──────────────────────────────────────────────────

    /** Retorna true si el tipo es superadministrador (nivel 100) */
    public function esSuperAdmin(): bool
    {
        return $this->nivel_acceso >= 100;
    }
}