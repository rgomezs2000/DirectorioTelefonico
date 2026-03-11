<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════════
//  TIPO EMAIL  |  Tabla: tipos_email
// ══════════════════════════════════════════════════════════════════
class TipoEmail extends BaseModel
{
    protected $table      = 'tipos_email';
    protected $primaryKey = 'id_tipo_email';
    const CREATED_AT      = null;
    const UPDATED_AT      = null;

    protected $fillable = ['nombre'];

    // ── Relaciones ────────────────────────────────────────────────
    public function emails()
    {
        return $this->hasMany(EmailContacto::class, 'id_tipo_email', 'id_tipo_email');
    }
}
