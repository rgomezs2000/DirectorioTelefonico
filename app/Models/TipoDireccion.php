<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════════
//  TIPO DIRECCION  |  Tabla: tipos_direccion
// ══════════════════════════════════════════════════════════════════
class TipoDireccion extends BaseModel
{
    protected $table      = 'tipos_direccion';
    protected $primaryKey = 'id_tipo_direccion';
    const CREATED_AT      = null;
    const UPDATED_AT      = null;

    protected $fillable = ['nombre', 'descripcion'];

    // ── Relaciones ────────────────────────────────────────────────
    public function direcciones()
    {
        return $this->hasMany(DireccionContacto::class, 'id_tipo_direccion', 'id_tipo_direccion');
    }
}