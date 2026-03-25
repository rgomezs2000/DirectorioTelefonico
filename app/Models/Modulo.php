<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Modulo extends BaseModel
{
    protected $table      = 'modulos';
    protected $primaryKey = 'id_modulo';

    protected $fillable = [
        'id_submenu',
        'nombre',
        'descripcion',
        'id_icono',
        'ruta',
        'controlador',
        'accion',
        'visible_menu',
        'orden',
        'activo',
    ];

    protected $casts = [
        'id_submenu'   => 'integer',
        'id_icono'     => 'integer',
        'visible_menu' => 'boolean',
        'orden'        => 'integer',
        'activo'       => 'boolean',
    ];

    public function submenu(): BelongsTo
    {
        return $this->belongsTo(Submenu::class, 'id_submenu', 'id_submenu');
    }

    public function icono(): BelongsTo
    {
        return $this->belongsTo(Icono::class, 'id_icono', 'id_icono');
    }

    public static function listarModulos(int $idSubmenu): Collection
    {
        return self::query()
            ->activos()
            ->where('id_submenu', $idSubmenu)
            ->where('visible_menu', true)
            ->with('icono')
            ->orderBy('orden')
            ->get()
            ->groupBy('id_submenu');
    }
}
