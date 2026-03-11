<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════════
//  DIVISION NIVEL 1  |  Tabla: divisiones_nivel1
//  (Departamento / Estado / Provincia)
// ══════════════════════════════════════════════════════════════════
class DivisionNivel1 extends BaseModel
{
    protected $table      = 'divisiones_nivel1';
    protected $primaryKey = 'id_nivel1';
    const UPDATED_AT      = null;

    protected $fillable = ['id_pais', 'nombre', 'codigo', 'tipo', 'capital', 'activo'];

    protected $casts = [
        'activo'    => 'boolean',
        'creado_en' => 'datetime',
    ];

    // ── Relaciones ────────────────────────────────────────────────
    public function pais()
    {
        return $this->belongsTo(Pais::class, 'id_pais', 'id_pais');
    }

    public function divisiones2()
    {
        return $this->hasMany(DivisionNivel2::class, 'id_nivel1', 'id_nivel1');
    }

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_nivel1', 'id_nivel1');
    }

    public function contactos()
    {
        return $this->hasMany(Contacto::class, 'id_nivel1', 'id_nivel1');
    }

    // ── Scopes ────────────────────────────────────────────────────
    public function scopePorPais(mixed $query, int $idPais): mixed
    {
        return $query->where('id_pais', $idPais);
    }

    public function scopePorTipo(mixed $query, string $tipo): mixed
    {
        return $query->where('tipo', $tipo);
    }
}
