<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

// ------------------------------------------------------------
// 4.2  TIPOS DE DIRECCIÓN
// ------------------------------------------------------------
/**
 * @property int         $id_tipo_direccion
 * @property string      $nombre
 * @property string|null $descripcion
 */
class TipoDireccion extends BaseModel
{
    protected $table      = 'tipos_direccion';
    protected $primaryKey = 'id_tipo_direccion';

    /** tabla sin timestamps */
    public $timestamps = false;
    const  CREATED_AT  = null;
    const  UPDATED_AT  = null;

    protected $fillable = ['nombre', 'descripcion'];

    public function direcciones(): HasMany
    {
        return $this->hasMany(DireccionContacto::class, 'id_tipo_direccion', 'id_tipo_direccion');
    }
}