<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════════
//  TELEFONO CONTACTO  |  Tabla: telefonos_contacto
// ══════════════════════════════════════════════════════════════════
class TelefonoContacto extends BaseModel
{
    protected $table      = 'telefonos_contacto';
    protected $primaryKey = 'id_telefono';
    const UPDATED_AT      = null;

    protected $fillable = [
        'id_contacto', 'id_tipo_telefono', 'id_pais',
        'numero', 'extension', 'es_principal', 'activo',
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

    public function tipoTelefono()
    {
        return $this->belongsTo(TipoTelefono::class, 'id_tipo_telefono', 'id_tipo_telefono');
    }

    public function pais()
    {
        return $this->belongsTo(Pais::class, 'id_pais', 'id_pais');
    }

    // ── Helpers ───────────────────────────────────────────────────
    public function numeroCompleto(): string
    {
        $prefijo = $this->pais?->codigo_telefono ?? '';
        $ext     = $this->extension ? " ext. {$this->extension}" : '';
        return "{$prefijo} {$this->numero}{$ext}";
    }
}
