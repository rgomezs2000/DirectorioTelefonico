<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════════
//  RED CONTACTO  |  Tabla: redes_contacto
// ══════════════════════════════════════════════════════════════════
class RedContacto extends BaseModel
{
    protected $table      = 'redes_contacto';
    protected $primaryKey = 'id_red_contacto';
    const UPDATED_AT      = null;

    protected $fillable = [
        'id_contacto', 'id_red_social',
        'usuario_red', 'url_perfil', 'es_principal', 'activo',
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

    public function redSocial()
    {
        return $this->belongsTo(RedSocial::class, 'id_red_social', 'id_red_social');
    }

    // ── Helpers ───────────────────────────────────────────────────
    public function urlPerfil(): string
    {
        if ($this->url_perfil) return $this->url_perfil;
        return ($this->redSocial?->url_base ?? '') . $this->usuario_red;
    }
}
