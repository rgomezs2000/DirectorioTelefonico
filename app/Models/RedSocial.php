<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════════
//  RED SOCIAL  |  Tabla: redes_sociales
// ══════════════════════════════════════════════════════════════════
class RedSocial extends BaseModel
{
    protected $table      = 'redes_sociales';
    protected $primaryKey = 'id_red_social';
    const CREATED_AT      = null;
    const UPDATED_AT      = null;

    protected $fillable = ['nombre', 'url_base', 'icono'];

    // ── Relaciones ────────────────────────────────────────────────
    public function redesContacto()
    {
        return $this->hasMany(RedContacto::class, 'id_red_social', 'id_red_social');
    }
}
