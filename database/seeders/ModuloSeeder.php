<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuloSeeder extends Seeder
{
    public function run(): void
    {
        $sub  = fn(string $nombre, string $menu) => DB::table('submenus')
            ->join('menus', 'menus.id_menu', '=', 'submenus.id_menu')
            ->where('submenus.nombre', $nombre)
            ->where('menus.nombre', $menu)
            ->value('submenus.id_submenu');

        $subN = fn(string $nombre) => DB::table('submenus')
            ->where('nombre', $nombre)
            ->value('id_submenu');

        $modulos = [
            // ── Administración › Usuarios ──────────────────────────
            ['id_submenu' => $sub('Usuarios', 'Administración'), 'nombre' => 'Listar Usuarios',   'descripcion' => 'Ver todos los usuarios',         'ruta' => '/admin/usuarios',             'controlador' => 'UsuarioController', 'accion' => 'index',        'visible_menu' => true,  'orden' => 1],
            ['id_submenu' => $sub('Usuarios', 'Administración'), 'nombre' => 'Crear Usuario',     'descripcion' => 'Registrar nuevo usuario',        'ruta' => '/admin/usuarios/crear',       'controlador' => 'UsuarioController', 'accion' => 'create',       'visible_menu' => true,  'orden' => 2],
            ['id_submenu' => $sub('Usuarios', 'Administración'), 'nombre' => 'Editar Usuario',    'descripcion' => 'Modificar datos de usuario',     'ruta' => '/admin/usuarios/{id}/editar', 'controlador' => 'UsuarioController', 'accion' => 'edit',         'visible_menu' => false, 'orden' => 3],
            ['id_submenu' => $sub('Usuarios', 'Administración'), 'nombre' => 'Eliminar Usuario',  'descripcion' => 'Dar de baja usuario',            'ruta' => null,                          'controlador' => 'UsuarioController', 'accion' => 'destroy',      'visible_menu' => false, 'orden' => 4],
            ['id_submenu' => $sub('Usuarios', 'Administración'), 'nombre' => 'Restablecer Clave', 'descripcion' => 'Resetear contraseña de usuario', 'ruta' => null,                          'controlador' => 'UsuarioController', 'accion' => 'resetPassword', 'visible_menu' => false, 'orden' => 5],
            // ── Administración › Permisos ──────────────────────────
            ['id_submenu' => $subN('Permisos'), 'nombre' => 'Ver Permisos',    'ruta' => '/admin/permisos',          'controlador' => 'PermisoController', 'accion' => 'index',   'visible_menu' => true,  'orden' => 1],
            ['id_submenu' => $subN('Permisos'), 'nombre' => 'Asignar Permiso', 'ruta' => '/admin/permisos/asignar',  'controlador' => 'PermisoController', 'accion' => 'store',   'visible_menu' => true,  'orden' => 2],
            ['id_submenu' => $subN('Permisos'), 'nombre' => 'Revocar Permiso', 'ruta' => null,                       'controlador' => 'PermisoController', 'accion' => 'destroy', 'visible_menu' => false, 'orden' => 3],
            // ── Administración › Menús y Módulos ──────────────────
            ['id_submenu' => $subN('Menús y Módulos'), 'nombre' => 'Gestionar Menús',    'ruta' => '/admin/menus',    'controlador' => 'MenuController',    'accion' => 'index', 'visible_menu' => true, 'orden' => 1],
            ['id_submenu' => $subN('Menús y Módulos'), 'nombre' => 'Gestionar Submenús', 'ruta' => '/admin/submenus', 'controlador' => 'SubmenuController', 'accion' => 'index', 'visible_menu' => true, 'orden' => 2],
            ['id_submenu' => $subN('Menús y Módulos'), 'nombre' => 'Gestionar Módulos',  'ruta' => '/admin/modulos',  'controlador' => 'ModuloController',  'accion' => 'index', 'visible_menu' => true, 'orden' => 3],
            // ── Directorio › Mis Contactos ─────────────────────────
            ['id_submenu' => $subN('Mis Contactos'), 'nombre' => 'Listar Contactos',  'descripcion' => 'Ver todos los contactos', 'ruta' => '/directorio/contactos',             'controlador' => 'ContactoController', 'accion' => 'index',   'visible_menu' => true,  'orden' => 1],
            ['id_submenu' => $subN('Mis Contactos'), 'nombre' => 'Ver Contacto',      'descripcion' => 'Detalle de contacto',     'ruta' => '/directorio/contactos/{id}',        'controlador' => 'ContactoController', 'accion' => 'show',    'visible_menu' => false, 'orden' => 2],
            ['id_submenu' => $subN('Mis Contactos'), 'nombre' => 'Editar Contacto',   'descripcion' => 'Modificar contacto',      'ruta' => '/directorio/contactos/{id}/editar', 'controlador' => 'ContactoController', 'accion' => 'edit',    'visible_menu' => false, 'orden' => 3],
            ['id_submenu' => $subN('Mis Contactos'), 'nombre' => 'Eliminar Contacto', 'descripcion' => 'Borrar contacto',         'ruta' => null,                                'controlador' => 'ContactoController', 'accion' => 'destroy', 'visible_menu' => false, 'orden' => 4],
            // ── Directorio › Nuevo Contacto ───────────────────────
            ['id_submenu' => $subN('Nuevo Contacto'), 'nombre' => 'Crear Contacto', 'descripcion' => 'Agregar nuevo contacto', 'ruta' => '/directorio/contactos/nuevo', 'controlador' => 'ContactoController', 'accion' => 'create', 'visible_menu' => true, 'orden' => 1],
            // ── Maestros (CRUD genérico) ───────────────────────────
            ['id_submenu' => $subN('Países'),          'nombre' => 'Gestionar Países',          'ruta' => '/maestros/paises',         'controlador' => 'PaisController',       'accion' => 'index', 'visible_menu' => true, 'orden' => 1],
            ['id_submenu' => $subN('Departamentos'),   'nombre' => 'Gestionar Departamentos',   'ruta' => '/maestros/nivel1',         'controlador' => 'Nivel1Controller',     'accion' => 'index', 'visible_menu' => true, 'orden' => 1],
            ['id_submenu' => $subN('Municipios'),      'nombre' => 'Gestionar Municipios',      'ruta' => '/maestros/nivel2',         'controlador' => 'Nivel2Controller',     'accion' => 'index', 'visible_menu' => true, 'orden' => 1],
            ['id_submenu' => $subN('Barrios'),         'nombre' => 'Gestionar Barrios',         'ruta' => '/maestros/nivel3',         'controlador' => 'Nivel3Controller',     'accion' => 'index', 'visible_menu' => true, 'orden' => 1],
            ['id_submenu' => $subN('Profesiones'),     'nombre' => 'Gestionar Profesiones',     'ruta' => '/maestros/profesiones',    'controlador' => 'ProfesionController',  'accion' => 'index', 'visible_menu' => true, 'orden' => 1],
            ['id_submenu' => $subN('Sexos / Géneros'), 'nombre' => 'Gestionar Sexos / Géneros', 'ruta' => '/maestros/sexos',          'controlador' => 'SexoController',       'accion' => 'index', 'visible_menu' => true, 'orden' => 1],
            ['id_submenu' => $subN('Categorías'),      'nombre' => 'Gestionar Categorías',      'ruta' => '/maestros/categorias',     'controlador' => 'CategoriaController',  'accion' => 'index', 'visible_menu' => true, 'orden' => 1],
            ['id_submenu' => $subN('Redes Sociales'),  'nombre' => 'Gestionar Redes Sociales',  'ruta' => '/maestros/redes-sociales', 'controlador' => 'RedSocialController',  'accion' => 'index', 'visible_menu' => true, 'orden' => 1],
            // ── Reportes ──────────────────────────────────────────
            ['id_submenu' => $subN('Contactos por País'),   'nombre' => 'Reporte: Contactos por País',   'ruta' => '/reportes/contactos-pais', 'controlador' => 'ReporteController', 'accion' => 'porPais',   'visible_menu' => true, 'orden' => 1],
            ['id_submenu' => $subN('Usuarios del Sistema'), 'nombre' => 'Reporte: Usuarios del Sistema', 'ruta' => '/reportes/usuarios',       'controlador' => 'ReporteController', 'accion' => 'usuarios',  'visible_menu' => true, 'orden' => 1],
            ['id_submenu' => $subN('Log de Auditoría'),     'nombre' => 'Reporte: Log de Auditoría',     'ruta' => '/reportes/auditoria',      'controlador' => 'ReporteController', 'accion' => 'auditoria', 'visible_menu' => true, 'orden' => 1],
            ['id_submenu' => $subN('Exportar Directorio'),  'nombre' => 'Reporte: Exportar Directorio',  'ruta' => '/reportes/exportar',       'controlador' => 'ReporteController', 'accion' => 'exportar',  'visible_menu' => true, 'orden' => 1],
        ];

        foreach ($modulos as $modulo) {
            $modulo['activo'] = true;
            DB::table('modulos')->updateOrInsert(
                ['id_submenu' => $modulo['id_submenu'], 'nombre' => $modulo['nombre']],
                $modulo
            );
        }
    }
}