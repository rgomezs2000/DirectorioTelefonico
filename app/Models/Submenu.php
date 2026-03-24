<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submenu extends BaseModel
{
    protected $table      = 'submenus';
    protected $primaryKey = 'id_submenu';

    protected $fillable = [
        'id_menu',
        'nombre',
        'descripcion',
        'id_icono',
        'ruta',
        'orden',
        'activo',
    ];

    protected $casts = [
        'id_menu'  => 'integer',
        'id_icono' => 'integer',
        'orden'    => 'integer',
        'activo'   => 'boolean',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }

    public function icono(): BelongsTo
    {
        return $this->belongsTo(Icono::class, 'id_icono', 'id_icono');
    }

    public static function listarSubmenu(int $idMenu): Collection
    {
        return self::query()
            ->activos()
            ->where('id_menu', $idMenu)
            ->with('icono')
            ->orderBy('orden')
            ->get()
            ->groupBy('id_menu');
    }
}
