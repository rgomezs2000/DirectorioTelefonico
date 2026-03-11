<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $icono = fn(string $comp) => DB::table('iconos')
            ->where('componente', $comp)
            ->value('id_icono');

        $menus = [
            [
                'nombre'      => 'Administración',
                'descripcion' => 'Gestión del sistema, usuarios y configuraciones',
                'id_icono'    => $icono('heroicon-o-cog-6-tooth'),
                'ruta'        => '/admin',
                'orden'       => 1,
            ],
            [
                'nombre'      => 'Maestros',
                'descripcion' => 'Catálogos y tablas maestras del sistema',
                'id_icono'    => $icono('heroicon-o-list-bullet'),
                'ruta'        => '/maestros',
                'orden'       => 2,
            ],
            [
                'nombre'      => 'Directorio',
                'descripcion' => 'Gestión de contactos y directorio telefónico',
                'id_icono'    => $icono('heroicon-o-book-open'),
                'ruta'        => '/directorio',
                'orden'       => 3,
            ],
            [
                'nombre'      => 'Reportes',
                'descripcion' => 'Informes y estadísticas del sistema',
                'id_icono'    => $icono('heroicon-o-chart-bar'),
                'ruta'        => '/reportes',
                'orden'       => 4,
            ],
            [
                'nombre'      => 'Cerrar Sesión',
                'descripcion' => 'Salir de la aplicación',
                'id_icono'    => $icono('heroicon-o-arrow-right-on-rectangle'),
                'ruta'        => '/logout',
                'orden'       => 99,
            ],
        ];

        foreach ($menus as $menu) {
            DB::table('menus')->updateOrInsert(
                ['nombre' => $menu['nombre']],
                array_merge($menu, ['activo' => true])
            );
        }
    }
}