<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════════
//  DIVISION NIVEL 2  |  Tabla: divisiones_nivel2
//  (Municipio / Ciudad / Cantón)
// ══════════════════════════════════════════════════════════════════
class DivisionNivel2 extends BaseModel
{
    protected $table      = 'divisiones_nivel2';
    protected $primaryKey = 'id_nivel2';
    const UPDATED_AT      = null;

    protected $fillable = [
        'id_nivel1', 'nombre', 'codigo', 'tipo', 'capital', 'poblacion', 'activo',
    ];

    protected $casts = [
        'poblacion' => 'integer',
        'activo'    => 'boolean',
        'creado_en' => 'datetime',
    ];

    // ── Relaciones ────────────────────────────────────────────────
    public function nivel1()
    {
        return $this->belongsTo(DivisionNivel1::class, 'id_nivel1', 'id_nivel1');
    }

    public function pais()
    {
        return $this->hasOneThrough(
            Pais::class, DivisionNivel1::class,
            'id_nivel1', 'id_pais', 'id_nivel1', 'id_pais'
        );
    }

    public function divisiones3()
    {
        return $this->hasMany(DivisionNivel3::class, 'id_nivel2', 'id_nivel2');
    }

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_nivel2', 'id_nivel2');
    }

    public function contactos()
    {
        return $this->hasMany(Contacto::class, 'id_nivel2', 'id_nivel2');
    }

    // ── Scopes ────────────────────────────────────────────────────
    public function scopePorNivel1(mixed $query, int $idNivel1): mixed
    {
        return $query->where('id_nivel1', $idNivel1);
    }

    public function scopePorCodigo(mixed $query, string $codigo): mixed
    {
        return $query->where('codigo', $codigo);
    }
}
