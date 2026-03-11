<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubmenuSeeder extends Seeder
{
    public function run(): void
    {
        $menu  = fn(string $n) => DB::table('menus')->where('nombre', $n)->value('id_menu');
        $icono = fn(string $c) => DB::table('iconos')->where('componente', $c)->value('id_icono');

        $submenus = [
            // ── Administración ────────────────────────────────────
            ['id_menu' => $menu('Administración'), 'nombre' => 'Usuarios',         'ruta' => '/admin/usuarios',        'id_icono' => $icono('heroicon-o-users'),                   'orden' => 1],
            ['id_menu' => $menu('Administración'), 'nombre' => 'Tipos de Usuario', 'ruta' => '/admin/tipos-usuario',   'id_icono' => $icono('heroicon-o-user-circle'),             'orden' => 2],
            ['id_menu' => $menu('Administración'), 'nombre' => 'Permisos',         'ruta' => '/admin/permisos',        'id_icono' => $icono('heroicon-o-shield-check'),            'orden' => 3],
            ['id_menu' => $menu('Administración'), 'nombre' => 'Menús y Módulos',  'ruta' => '/admin/menus',           'id_icono' => $icono('heroicon-o-squares-2x2'),             'orden' => 4],
            ['id_menu' => $menu('Administración'), 'nombre' => 'Auditoría',        'ruta' => '/admin/auditoria',       'id_icono' => $icono('heroicon-o-clipboard-document-list'), 'orden' => 5],
            // ── Maestros ──────────────────────────────────────────
            ['id_menu' => $menu('Maestros'), 'nombre' => 'Países',          'ruta' => '/maestros/paises',          'id_icono' => $icono('heroicon-o-globe-alt'),       'orden' => 1],
            ['id_menu' => $menu('Maestros'), 'nombre' => 'Departamentos',   'ruta' => '/maestros/nivel1',          'id_icono' => $icono('heroicon-o-map-pin'),         'orden' => 2],
            ['id_menu' => $menu('Maestros'), 'nombre' => 'Municipios',      'ruta' => '/maestros/nivel2',          'id_icono' => $icono('heroicon-o-building-office'), 'orden' => 3],
            ['id_menu' => $menu('Maestros'), 'nombre' => 'Barrios',         'ruta' => '/maestros/nivel3',          'id_icono' => $icono('heroicon-o-map-pin'),         'orden' => 4],
            ['id_menu' => $menu('Maestros'), 'nombre' => 'Profesiones',     'ruta' => '/maestros/profesiones',     'id_icono' => $icono('heroicon-o-briefcase'),       'orden' => 5],
            ['id_menu' => $menu('Maestros'), 'nombre' => 'Sexos / Géneros', 'ruta' => '/maestros/sexos',           'id_icono' => $icono('heroicon-o-user'),            'orden' => 6],
            ['id_menu' => $menu('Maestros'), 'nombre' => 'Categorías',      'ruta' => '/maestros/categorias',      'id_icono' => $icono('heroicon-o-tag'),             'orden' => 7],
            ['id_menu' => $menu('Maestros'), 'nombre' => 'Redes Sociales',  'ruta' => '/maestros/redes-sociales',  'id_icono' => $icono('heroicon-o-share'),           'orden' => 8],
            // ── Directorio ────────────────────────────────────────
            ['id_menu' => $menu('Directorio'), 'nombre' => 'Mis Contactos',    'ruta' => '/directorio/contactos',        'id_icono' => $icono('heroicon-o-phone'),            'orden' => 1],
            ['id_menu' => $menu('Directorio'), 'nombre' => 'Nuevo Contacto',   'ruta' => '/directorio/contactos/nuevo',  'id_icono' => $icono('heroicon-o-user-plus'),        'orden' => 2],
            ['id_menu' => $menu('Directorio'), 'nombre' => 'Mis Grupos',       'ruta' => '/directorio/grupos',           'id_icono' => $icono('heroicon-o-user-group'),       'orden' => 3],
            ['id_menu' => $menu('Directorio'), 'nombre' => 'Buscar Contacto',  'ruta' => '/directorio/buscar',           'id_icono' => $icono('heroicon-o-magnifying-glass'), 'orden' => 4],
            // ── Reportes ──────────────────────────────────────────
            ['id_menu' => $menu('Reportes'), 'nombre' => 'Contactos por País',   'ruta' => '/reportes/contactos-pais', 'id_icono' => $icono('heroicon-o-chart-bar'),       'orden' => 1],
            ['id_menu' => $menu('Reportes'), 'nombre' => 'Usuarios del Sistema', 'ruta' => '/reportes/usuarios',       'id_icono' => $icono('heroicon-o-users'),           'orden' => 2],
            ['id_menu' => $menu('Reportes'), 'nombre' => 'Log de Auditoría',     'ruta' => '/reportes/auditoria',      'id_icono' => $icono('heroicon-o-document-text'),   'orden' => 3],
            ['id_menu' => $menu('Reportes'), 'nombre' => 'Exportar Directorio',  'ruta' => '/reportes/exportar',       'id_icono' => $icono('heroicon-o-arrow-down-tray'), 'orden' => 4],
        ];

        foreach ($submenus as $sub) {
            DB::table('submenus')->updateOrInsert(
                ['id_menu' => $sub['id_menu'], 'nombre' => $sub['nombre']],
                array_merge($sub, ['activo' => true])
            );
        }
    }
}