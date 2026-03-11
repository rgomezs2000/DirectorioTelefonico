<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

// ============================================================
//  DETALLE DE CONTACTOS
//  TelefonoContacto · EmailContacto · DireccionContacto · RedContacto
// ============================================================


// ------------------------------------------------------------
// 4.6  TELÉFONOS DE CONTACTO
// ------------------------------------------------------------
/**
 * @property int         $id_telefono
 * @property int         $id_contacto
 * @property int         $id_tipo_telefono
 * @property int|null    $id_pais
 * @property string      $numero
 * @property string|null $extension
 * @property bool        $es_principal
 * @property bool        $activo
 * @property string      $creado_en
 */
class TelefonoContacto extends BaseModel
{
    protected $table      = 'telefonos_contacto';
    protected $primaryKey = 'id_telefono';

    /** sin actualizado_en */
    public $timestamps = false;
    const  CREATED_AT  = 'creado_en';
    const  UPDATED_AT  = null;

    protected $fillable = [
        'id_contacto',
        'id_tipo_telefono',
        'id_pais',
        'numero',
        'extension',
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
        return $this->belongsTo(TipoTelefono::class, 'id_tipo_telefono', 'id_tipo_telefono');
    }

    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class, 'id_pais', 'id_pais');
    }

    /** Número con extensión formateado */
    public function numeroCompleto(): string
    {
        return $this->extension
            ? "{$this->numero} ext. {$this->extension}"
            : $this->numero;
    }
}
