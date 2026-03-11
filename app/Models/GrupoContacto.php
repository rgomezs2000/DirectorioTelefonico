<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int         $id_grupo
 * @property int         $id_usuario
 * @property string      $nombre
 * @property string|null $descripcion
 * @property string|null $color_hex
 * @property bool        $activo
 * @property string      $creado_en
 */
class GrupoContacto extends BaseModel
{
    protected $table      = 'grupos_contacto';
    protected $primaryKey = 'id_grupo';

    /** sin actualizado_en en esta tabla */
    public $timestamps = false;
    const  CREATED_AT  = 'creado_en';
    const  UPDATED_AT  = null;

    protected $fillable = [
        'id_usuario',
        'nombre',
        'descripcion',
        'color_hex',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // ── Relaciones ────────────────────────────────────────────────

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function contactos(): BelongsToMany
    {
        return $this->belongsToMany(
            Contacto::class,
            'contacto_grupo',
            'id_grupo',
            'id_contacto'
        )->withPivot('agregado_en');
    }

    // ── Scopes propios ────────────────────────────────────────────

    /** Filtra grupos del usuario dado */
    public function scopeDeUsuario(Builder $query, int $idUsuario): Builder
    {
        return $query->where('id_usuario', $idUsuario);
    }
}