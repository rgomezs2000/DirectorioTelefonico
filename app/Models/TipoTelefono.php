<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

// ============================================================
//  CATÁLOGOS SIMPLES DE LA SECCIÓN 4
//  Cinco modelos livianos en un solo archivo para mantener
//  el proyecto organizado.
// ============================================================


// ------------------------------------------------------------
// 4.1  TIPOS DE TELÉFONO
// ------------------------------------------------------------
/**
 * @property int         $id_tipo_telefono
 * @property string      $nombre
 * @property string|null $descripcion
 */
class TipoTelefono extends BaseModel
{
    protected $table      = 'tipos_telefono';
    protected $primaryKey = 'id_tipo_telefono';

    /** tabla sin timestamps */
    public $timestamps = false;
    const  CREATED_AT  = null;
    const  UPDATED_AT  = null;

    protected $fillable = ['nombre', 'descripcion'];

    public function telefonos(): HasMany
    {
        return $this->hasMany(TelefonoContacto::class, 'id_tipo_telefono', 'id_tipo_telefono');
    }
}