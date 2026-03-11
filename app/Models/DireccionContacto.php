<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

// ------------------------------------------------------------
// 4.8  DIRECCIONES FÍSICAS DE CONTACTO
// ------------------------------------------------------------
/**
 * @property int         $id_direccion
 * @property int         $id_contacto
 * @property int         $id_tipo_direccion
 * @property int|null    $id_pais
 * @property int|null    $id_nivel1
 * @property int|null    $id_nivel2
 * @property int|null    $id_nivel3
 * @property string      $direccion_linea1
 * @property string|null $direccion_linea2
 * @property string|null $codigo_postal
 * @property string|null $referencia
 * @property bool        $es_principal
 * @property bool        $activo
 * @property string      $creado_en
 */
class DireccionContacto extends BaseModel
{
    protected $table      = 'direcciones_contacto';
    protected $primaryKey = 'id_direccion';

    /** sin actualizado_en */
    public $timestamps = false;
    const  CREATED_AT  = 'creado_en';
    const  UPDATED_AT  = null;

    protected $fillable = [
        'id_contacto',
        'id_tipo_direccion',
        'id_pais',
        'id_nivel1',
        'id_nivel2',
        'id_nivel3',
        'direccion_linea1',
        'direccion_linea2',
        'codigo_postal',
        'referencia',
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
        return $this->belongsTo(TipoDireccion::class, 'id_tipo_direccion', 'id_tipo_direccion');
    }

    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class, 'id_pais', 'id_pais');
    }

    public function nivel1(): BelongsTo
    {
        return $this->belongsTo(DivisionNivel1::class, 'id_nivel1', 'id_nivel1');
    }

    public function nivel2(): BelongsTo
    {
        return $this->belongsTo(DivisionNivel2::class, 'id_nivel2', 'id_nivel2');
    }

    public function nivel3(): BelongsTo
    {
        return $this->belongsTo(DivisionNivel3::class, 'id_nivel3', 'id_nivel3');
    }

    /** Dirección completa en una sola línea */
    public function resumen(): string
    {
        $partes = array_filter([
            $this->direccion_linea1,
            $this->direccion_linea2,
            $this->nivel2?->nombre,
            $this->nivel1?->nombre,
            $this->pais?->nombre,
        ]);
        return implode(', ', $partes);
    }
}

