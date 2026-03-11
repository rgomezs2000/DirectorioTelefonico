<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

// ------------------------------------------------------------
// 4.9  REDES SOCIALES
// ------------------------------------------------------------
/**
 * @property int         $id_red_social
 * @property string      $nombre
 * @property string|null $url_base
 * @property string|null $icono
 */
class RedSocial extends BaseModel
{
    protected $table      = 'redes_sociales';
    protected $primaryKey = 'id_red_social';

    /** tabla sin timestamps */
    public $timestamps = false;
    const  CREATED_AT  = null;
    const  UPDATED_AT  = null;

    protected $fillable = ['nombre', 'url_base', 'icono'];

    public function redesContacto(): HasMany
    {
        return $this->hasMany(RedContacto::class, 'id_red_social', 'id_red_social');
    }

    /**
     * Genera la URL completa del perfil dado el handle del usuario.
     * Ejemplo: RedSocial::find(2)->urlPerfil('usuario123')
     *          → 'https://instagram.com/usuario123'
     */
    public function urlPerfil(string $handle): string
    {
        return ($this->url_base ?? '') . $handle;
    }
}
