<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

// ------------------------------------------------------------
// 4.9  REDES SOCIALES DE CONTACTO
// ------------------------------------------------------------
/**
 * @property int         $id_red_contacto
 * @property int         $id_contacto
 * @property int         $id_red_social
 * @property string      $usuario_red
 * @property string|null $url_perfil
 * @property bool        $es_principal
 * @property bool        $activo
 * @property string      $creado_en
 */
class RedContacto extends BaseModel
{
    protected $table      = 'redes_contacto';
    protected $primaryKey = 'id_red_contacto';

    /** sin actualizado_en */
    public $timestamps = false;
    const  CREATED_AT  = 'creado_en';
    const  UPDATED_AT  = null;

    protected $fillable = [
        'id_contacto',
        'id_red_social',
        'usuario_red',
        'url_perfil',
        'es_principal',
        'activo',
    ];

    protected $casts = [
        'es_principal' => 'boolean',
        'activo'       => 'boolean',
    ];

    public function contacto(): BelongsTo
    {
        return $this->belongsTo(Contacto::class, 'id_contacto', 'id_contacto');
    }

    public function redSocial(): BelongsTo
    {
        return $this->belongsTo(RedSocial::class, 'id_red_social', 'id_red_social');
    }

    /** Devuelve la URL del perfil (calculada si no se almacenó explícitamente) */
    public function urlPerfilCompleta(): string
    {
        return $this->url_perfil
            ?? ($this->redSocial?->url_base ?? '') . $this->usuario_red;
    }
}
