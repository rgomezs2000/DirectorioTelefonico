<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════════
//  TIPO DE USUARIO  |  Tabla: tipos_usuario
// ══════════════════════════════════════════════════════════════════
class TipoUsuario extends BaseModel
{
    protected $table      = 'tipos_usuario';
    protected $primaryKey = 'id_tipo_usuario';
    const UPDATED_AT      = null;

    protected $fillable = ['nombre', 'descripcion', 'nivel_acceso', 'activo'];

    protected $casts = [
        'nivel_acceso' => 'integer',
        'activo'       => 'boolean',
        'creado_en'    => 'datetime',
    ];

    // ── Relaciones ────────────────────────────────────────────────
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_tipo_usuario', 'id_tipo_usuario');
    }

    // ── Scopes ────────────────────────────────────────────────────
    public function scopeConNivelMinimo(mixed $query, int $nivel): mixed
    {
        return $query->where('nivel_acceso', '>=', $nivel);
    }

    public function scopePorNivel(mixed $query): mixed
    {
        return $query->orderByDesc('nivel_acceso');
    }
}
