<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════════
//  DIVISION NIVEL 3  |  Tabla: divisiones_nivel3
//  (Barrio / Vereda / Parroquia / Colonia)
// ══════════════════════════════════════════════════════════════════
class DivisionNivel3 extends BaseModel
{
    protected $table      = 'divisiones_nivel3';
    protected $primaryKey = 'id_nivel3';
    const UPDATED_AT      = null;

    protected $fillable = [
        'id_nivel2', 'nombre', 'codigo', 'tipo', 'codigo_postal', 'activo',
    ];

    protected $casts = [
        'activo'    => 'boolean',
        'creado_en' => 'datetime',
    ];

    // ── Relaciones ────────────────────────────────────────────────
    public function nivel2()
    {
        return $this->belongsTo(DivisionNivel2::class, 'id_nivel2', 'id_nivel2');
    }

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_nivel3', 'id_nivel3');
    }

    public function contactos()
    {
        return $this->hasMany(Contacto::class, 'id_nivel3', 'id_nivel3');
    }

    // ── Scopes ────────────────────────────────────────────────────
    public function scopePorNivel2(mixed $query, int $idNivel2): mixed
    {
        return $query->where('id_nivel2', $idNivel2);
    }

    public function scopePorCodigoPostal(mixed $query, string $cp): mixed
    {
        return $query->where('codigo_postal', $cp);
    }
}