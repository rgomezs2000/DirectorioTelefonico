<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════════
//  TIPO TELEFONO  |  Tabla: tipos_telefono
// ══════════════════════════════════════════════════════════════════
class TipoTelefono extends BaseModel
{
    protected $table      = 'tipos_telefono';
    protected $primaryKey = 'id_tipo_telefono';
    const CREATED_AT      = null;
    const UPDATED_AT      = null;

    protected $fillable = ['nombre', 'descripcion'];

    // ── Relaciones ────────────────────────────────────────────────
    public function telefonos()
    {
        return $this->hasMany(TelefonoContacto::class, 'id_tipo_telefono', 'id_tipo_telefono');
    }
}