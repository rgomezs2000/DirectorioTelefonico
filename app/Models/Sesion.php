<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════════
//  SESION  |  Tabla: sesiones
// ══════════════════════════════════════════════════════════════════
class Sesion extends BaseModel
{
    protected $table      = 'sesiones';
    protected $primaryKey = 'id_sesion';
    const UPDATED_AT      = null;

    protected $hidden   = ['token_sesion'];
    protected $fillable = [
        'id_usuario', 'token_sesion', 'ip_origen',
        'user_agent', 'dispositivo', 'activa', 'expira_en', 'cerrada_en',
    ];

    protected $casts = [
        'activa'     => 'boolean',
        'expira_en'  => 'datetime',
        'cerrada_en' => 'datetime',
        'creado_en'  => 'datetime',
    ];

    // ── Relaciones ────────────────────────────────────────────────
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // ── Scopes ────────────────────────────────────────────────────
    public function scopeActivas(mixed $query): mixed
    {
        return $query->where('activa', true)->where('expira_en', '>', now());
    }

    // ── Helpers ───────────────────────────────────────────────────
    public function cerrar(): bool
    {
        return $this->update(['activa' => false, 'cerrada_en' => now()]);
    }

    public function haExpirado(): bool
    {
        return $this->expira_en && $this->expira_en->isPast();
    }
}
