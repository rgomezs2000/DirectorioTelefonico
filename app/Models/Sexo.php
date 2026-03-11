<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int         $id_sexo
 * @property string      $nombre
 * @property string      $abreviatura
 * @property string|null $descripcion
 * @property bool        $activo
 * @property string      $creado_en
 */
class Sexo extends BaseModel
{
    protected $table      = 'sexos';
    protected $primaryKey = 'id_sexo';

    /** sin actualizado_en en esta tabla */
    public $timestamps  = false;
    const  CREATED_AT   = 'creado_en';
    const  UPDATED_AT   = null;

    protected $fillable = [
        'nombre',
        'abreviatura',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // ── Relaciones ────────────────────────────────────────────────

    public function usuarios(): HasMany
    {
        return $this->hasMany(Usuario::class, 'id_sexo', 'id_sexo');
    }

    public function contactos(): HasMany
    {
        return $this->hasMany(Contacto::class, 'id_sexo', 'id_sexo');
    }
}