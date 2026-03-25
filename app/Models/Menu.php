<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends BaseModel
{
    protected $table      = 'menus';
    protected $primaryKey = 'id_menu';

    protected $fillable = [
        'nombre',
        'descripcion',
        'id_icono',
        'ruta',
        'orden',
        'activo',
    ];

    protected $casts = [
        'id_icono' => 'integer',
        'orden'    => 'integer',
        'activo'   => 'boolean',
    ];

    public function icono(): BelongsTo
    {
        return $this->belongsTo(Icono::class, 'id_icono', 'id_icono');
    }

    public static function listarMenu(): Collection
    {
        return self::query()
            ->activos()
            ->with('icono')
            ->orderBy('orden')
            ->get();
    }
}
