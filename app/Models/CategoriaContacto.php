<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

// ------------------------------------------------------------
// 4.4  CATEGORÍAS DE CONTACTO
// ------------------------------------------------------------
/**
 * @property int         $id_categoria
 * @property string      $nombre
 * @property string|null $descripcion
 * @property string|null $color_hex
 * @property string|null $icono
 * @property bool        $activo
 */
class CategoriaContacto extends BaseModel
{
    protected $table      = 'categorias_contacto';
    protected $primaryKey = 'id_categoria';

    /** tabla sin timestamps */
    public $timestamps = false;
    const  CREATED_AT  = null;
    const  UPDATED_AT  = null;

    protected $fillable = [
        'nombre',
        'descripcion',
        'color_hex',
        'icono',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function contactos(): HasMany
    {
        return $this->hasMany(Contacto::class, 'id_categoria', 'id_categoria');
    }
}