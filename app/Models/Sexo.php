<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════════
//  SEXO  |  Tabla: sexos
// ══════════════════════════════════════════════════════════════════
class Sexo extends BaseModel
{
    protected $table      = 'sexos';
    protected $primaryKey = 'id_sexo';
    const UPDATED_AT      = null;

    protected $fillable = ['nombre', 'abreviatura', 'descripcion', 'activo'];

    protected $casts = [
        'activo'    => 'boolean',
        'creado_en' => 'datetime',
    ];

    // ── Relaciones ────────────────────────────────────────────────
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_sexo', 'id_sexo');
    }

    public function contactos()
    {
        return $this->hasMany(Contacto::class, 'id_sexo', 'id_sexo');
    }

    // ── Scopes ────────────────────────────────────────────────────
    public function scopePorAbreviatura(mixed $query, string $abr): mixed
    {
        return $query->where('abreviatura', strtoupper($abr));
    }
}
