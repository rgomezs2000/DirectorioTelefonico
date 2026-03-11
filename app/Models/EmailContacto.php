<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

// ------------------------------------------------------------
// 4.7  EMAILS DE CONTACTO
// ------------------------------------------------------------
/**
 * @property int         $id_email
 * @property int         $id_contacto
 * @property int         $id_tipo_email
 * @property string      $email
 * @property bool        $es_principal
 * @property bool        $activo
 * @property string      $creado_en
 */
class EmailContacto extends BaseModel
{
    protected $table      = 'emails_contacto';
    protected $primaryKey = 'id_email';

    /** sin actualizado_en */
    public $timestamps = false;
    const  CREATED_AT  = 'creado_en';
    const  UPDATED_AT  = null;

    protected $fillable = [
        'id_contacto',
        'id_tipo_email',
        'email',
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

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoEmail::class, 'id_tipo_email', 'id_tipo_email');
    }
}

