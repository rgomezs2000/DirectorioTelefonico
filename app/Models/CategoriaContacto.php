<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════════
//  CATEGORIA CONTACTO  |  Tabla: categorias_contacto
// ══════════════════════════════════════════════════════════════════
class CategoriaContacto extends BaseModel
{
    protected $table      = 'categorias_contacto';
    protected $primaryKey = 'id_categoria';
    const UPDATED_AT      = null;
    const CREATED_AT      = null;

    protected $fillable = ['nombre', 'descripcion', 'color_hex', 'icono', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    // ── Relaciones ────────────────────────────────────────────────
    public function contactos()
    {
        return $this->hasMany(Contacto::class, 'id_categoria', 'id_categoria');
    }
}
