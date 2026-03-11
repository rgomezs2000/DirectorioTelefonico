<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

// ------------------------------------------------------------
// 4.3  TIPOS DE EMAIL
// ------------------------------------------------------------
/**
 * @property int    $id_tipo_email
 * @property string $nombre
 */
class TipoEmail extends BaseModel
{
    protected $table      = 'tipos_email';
    protected $primaryKey = 'id_tipo_email';

    /** tabla sin timestamps */
    public $timestamps = false;
    const  CREATED_AT  = null;
    const  UPDATED_AT  = null;

    protected $fillable = ['nombre'];

    public function emails(): HasMany
    {
        return $this->hasMany(EmailContacto::class, 'id_tipo_email', 'id_tipo_email');
    }
}
