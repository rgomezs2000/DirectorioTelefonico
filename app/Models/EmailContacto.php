<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════════
//  EMAIL CONTACTO  |  Tabla: emails_contacto
// ══════════════════════════════════════════════════════════════════
class EmailContacto extends BaseModel
{
    protected $table      = 'emails_contacto';
    protected $primaryKey = 'id_email';
    const UPDATED_AT      = null;

    protected $fillable = [
        'id_contacto', 'id_tipo_email', 'email', 'es_principal', 'activo',
    ];

    protected $casts = [
        'es_principal' => 'boolean',
        'activo'       => 'boolean',
        'creado_en'    => 'datetime',
    ];

    // ── Relaciones ────────────────────────────────────────────────
    public function contacto()
    {
        return $this->belongsTo(Contacto::class, 'id_contacto', 'id_contacto');
    }

    public function tipoEmail()
    {
        return $this->belongsTo(TipoEmail::class, 'id_tipo_email', 'id_tipo_email');
    }
}
