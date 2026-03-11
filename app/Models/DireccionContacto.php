<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════════
//  DIRECCION CONTACTO  |  Tabla: direcciones_contacto
// ══════════════════════════════════════════════════════════════════
class DireccionContacto extends BaseModel
{
    protected $table      = 'direcciones_contacto';
    protected $primaryKey = 'id_direccion';
    const UPDATED_AT      = null;

    protected $fillable = [
        'id_contacto', 'id_tipo_direccion',
        'id_pais', 'id_nivel1', 'id_nivel2', 'id_nivel3',
        'direccion_linea1', 'direccion_linea2', 'codigo_postal',
        'referencia', 'es_principal', 'activo',
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

    public function tipoDireccion()
    {
        return $this->belongsTo(TipoDireccion::class, 'id_tipo_direccion', 'id_tipo_direccion');
    }

    public function pais()
    {
        return $this->belongsTo(Pais::class, 'id_pais', 'id_pais');
    }

    public function nivel1()
    {
        return $this->belongsTo(DivisionNivel1::class, 'id_nivel1', 'id_nivel1');
    }

    public function nivel2()
    {
        return $this->belongsTo(DivisionNivel2::class, 'id_nivel2', 'id_nivel2');
    }

    public function nivel3()
    {
        return $this->belongsTo(DivisionNivel3::class, 'id_nivel3', 'id_nivel3');
    }

    // ── Helpers ───────────────────────────────────────────────────
    public function direccionFormateada(): string
    {
        $partes = array_filter([
            $this->direccion_linea1,
            $this->direccion_linea2,
            $this->nivel3?->nombre,
            $this->nivel2?->nombre,
            $this->nivel1?->nombre,
            $this->pais?->nombre,
            $this->codigo_postal,
        ]);
        return implode(', ', $partes);
    }
}

