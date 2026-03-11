<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════════
//  GRUPO CONTACTO  |  Tabla: grupos_contacto
// ══════════════════════════════════════════════════════════════════
class GrupoContacto extends BaseModel
{
    protected $table      = 'grupos_contacto';
    protected $primaryKey = 'id_grupo';
    const UPDATED_AT      = null;

    protected $fillable = ['id_usuario', 'nombre', 'descripcion', 'color_hex', 'activo'];

    protected $casts = [
        'activo'    => 'boolean',
        'creado_en' => 'datetime',
    ];

    // ── Relaciones ────────────────────────────────────────────────
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function contactos()
    {
        return $this->belongsToMany(
            Contacto::class,
            'contacto_grupo',
            'id_grupo',
            'id_contacto'
        )->withPivot('agregado_en');
    }
}
