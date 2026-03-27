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

    public static function mostrarMenu(?string $ruta = null): ?array
    {
        $rutaNormalizada = self::normalizarRutaModulo($ruta);

        if ($rutaNormalizada === '/') {
            return [
                'tipo' => 'inicio',
                'nombre' => 'Inicio',
                'ruta_actual' => '/',
                'breadcrumb' => ['Inicio'],
                'menu' => null,
                'submenu' => null,
                'modulo' => null,
            ];
        }

        $consulta = self::query()
            ->from('menus')
            ->leftJoin('submenus', static function ($join): void {
                $join->on('submenus.id_menu', '=', 'menus.id_menu')
                    ->where('submenus.activo', true);
            })
            ->leftJoin('modulos', static function ($join): void {
                $join->on('modulos.id_submenu', '=', 'submenus.id_submenu')
                    ->where('modulos.activo', true);
            })
            ->where('menus.activo', true)
            ->where(static function ($query) use ($rutaNormalizada): void {
                $query->where('menus.ruta', $rutaNormalizada)
                    ->orWhere('submenus.ruta', $rutaNormalizada)
                    ->orWhere('modulos.ruta', $rutaNormalizada);
            })
            ->select([
                'menus.id_menu',
                'menus.nombre as menu_nombre',
                'menus.ruta as menu_ruta',
                'submenus.id_submenu',
                'submenus.nombre as submenu_nombre',
                'submenus.ruta as submenu_ruta',
                'modulos.id_modulo',
                'modulos.nombre as modulo_nombre',
                'modulos.ruta as modulo_ruta',
            ])
            ->orderByRaw('CASE WHEN modulos.ruta = ? THEN 1 WHEN submenus.ruta = ? THEN 2 ELSE 3 END', [
                $rutaNormalizada,
                $rutaNormalizada,
            ])
            ->first();

        if ($consulta === null) {
            return null;
        }

        $esModulo = isset($consulta->id_modulo) && $consulta->modulo_ruta === $rutaNormalizada;
        $esSubmenu = ! $esModulo && isset($consulta->id_submenu) && $consulta->submenu_ruta === $rutaNormalizada;

        if ($esModulo) {
            return [
                'tipo' => 'modulo',
                'nombre' => (string) $consulta->modulo_nombre,
                'ruta_actual' => $rutaNormalizada,
                'breadcrumb' => array_values(array_filter([
                    'Inicio',
                    $consulta->menu_nombre,
                    $consulta->submenu_nombre,
                    $consulta->modulo_nombre,
                ])),
                'menu' => ['id' => (int) $consulta->id_menu, 'nombre' => (string) $consulta->menu_nombre, 'ruta' => (string) $consulta->menu_ruta],
                'submenu' => ['id' => (int) $consulta->id_submenu, 'nombre' => (string) $consulta->submenu_nombre, 'ruta' => (string) $consulta->submenu_ruta],
                'modulo' => ['id' => (int) $consulta->id_modulo, 'nombre' => (string) $consulta->modulo_nombre, 'ruta' => (string) $consulta->modulo_ruta],
            ];
        }

        if ($esSubmenu) {
            return [
                'tipo' => 'submenu',
                'nombre' => (string) $consulta->submenu_nombre,
                'ruta_actual' => $rutaNormalizada,
                'breadcrumb' => array_values(array_filter(['Inicio', $consulta->menu_nombre, $consulta->submenu_nombre])),
                'menu' => ['id' => (int) $consulta->id_menu, 'nombre' => (string) $consulta->menu_nombre, 'ruta' => (string) $consulta->menu_ruta],
                'submenu' => ['id' => (int) $consulta->id_submenu, 'nombre' => (string) $consulta->submenu_nombre, 'ruta' => (string) $consulta->submenu_ruta],
                'modulo' => null,
            ];
        }

        return [
            'tipo' => 'menu',
            'nombre' => (string) $consulta->menu_nombre,
            'ruta_actual' => $rutaNormalizada,
            'breadcrumb' => array_values(array_filter(['Inicio', $consulta->menu_nombre])),
            'menu' => ['id' => (int) $consulta->id_menu, 'nombre' => (string) $consulta->menu_nombre, 'ruta' => (string) $consulta->menu_ruta],
            'submenu' => null,
            'modulo' => null,
        ];
    }

    private static function normalizarRutaModulo(?string $ruta): string
    {
        $rutaNormalizada = trim((string) $ruta);

        if ($rutaNormalizada === '' || strtolower($rutaNormalizada) === 'null') {
            return '/';
        }

        $rutaNormalizada = str_replace('-', '/', $rutaNormalizada);

        if ($rutaNormalizada === '' || $rutaNormalizada === '/') {
            return '/';
        }

        return '/'.ltrim($rutaNormalizada, '/');
    }
}
