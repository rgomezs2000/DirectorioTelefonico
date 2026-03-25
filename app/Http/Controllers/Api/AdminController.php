<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Modulo;
use App\Models\Submenu;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Throwable;

class AdminController extends Controller
{
    public function listarMenu(): JsonResponse
    {
        try {
            $menus = Menu::listarMenu();

            if ($menus->isEmpty()) {
                return response()->json([
                    'codigo' => 408,
                    'mensaje' => 'no hay modulos configurados en el sistema, consulte con su administrador',
                    'data' => [],
                ]);
            }

            $resultado = $menus->map(function ($menu) {
                $submenusAgrupados = Submenu::listarSubmenu((int) $menu->id_menu);
                $submenus = $submenusAgrupados->get($menu->id_menu, collect())->values();

                $menu->submenus = $submenus->map(function ($submenu) {
                    $modulosAgrupados = Modulo::listarModulos((int) $submenu->id_submenu);
                    $submenu->modulos = $modulosAgrupados->get($submenu->id_submenu, collect())->values();

                    return $submenu;
                })->values();

                return $menu;
            })->values();

            return response()->json([
                'codigo' => 200,
                'mensaje' => 'modulos desplegados',
                'data' => $resultado,
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'codigo' => 500,
                'mensaje' => 'Error del servidor',
                'error' => Str::limit(trim((string) $exception->getMessage()) ?: 'Error inesperado', 120),
            ], 500);
        }
    }
}
