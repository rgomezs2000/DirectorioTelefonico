<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermisoSeeder extends Seeder
{
    public function run(): void
    {
        // Helper: obtiene id_tipo_usuario por nombre de rol
        $tipo = fn(string $nombre): int => DB::table('tipos_usuario')
            ->where('nombre', $nombre)
            ->value('id_tipo_usuario');

        // Helper: obtiene id_menu por nombre
        $menu = fn(string $nombre): int => DB::table('menus')
            ->where('nombre', $nombre)
            ->value('id_menu');

        // Shorthand para insertar un permiso de menú evitando duplicados
        $insertar = function (
            int $idTipo,
            int $idMenu,
            bool $ver,
            bool $crear,
            bool $editar,
            bool $eliminar,
            bool $exportar,
            bool $imprimir
        ): void {
            DB::table('permisos')->updateOrInsert(
                [
                    'id_tipo_usuario' => $idTipo,
                    'id_menu'         => $idMenu,
                    'id_submenu'      => null,
                    'id_modulo'       => null,
                ],
                [
                    'puede_ver'      => $ver,
                    'puede_crear'    => $crear,
                    'puede_editar'   => $editar,
                    'puede_eliminar' => $eliminar,
                    'puede_exportar' => $exportar,
                    'puede_imprimir' => $imprimir,
                ]
            );
        };

        // ── Superadministrador: acceso total a TODOS los menús ─────
        $idSuper = $tipo('Superadministrador');
        foreach (DB::table('menus')->pluck('id_menu') as $idMenu) {
            $insertar($idSuper, $idMenu, true, true, true, true, true, true);
        }

        // ── Administrador: acceso total excepto "Cerrar Sesión" ────
        $idAdmin = $tipo('Administrador');
        foreach (DB::table('menus')->where('nombre', '<>', 'Cerrar Sesión')->pluck('id_menu') as $idMenu) {
            $insertar($idAdmin, $idMenu, true, true, true, true, true, true);
        }

        // ── Moderador: Directorio + Maestros + Reportes (sin eliminar) ──
        $idMod    = $tipo('Moderador');
        $menusMod = ['Directorio', 'Maestros', 'Reportes'];
        foreach ($menusMod as $nombreMenu) {
            $insertar($idMod, $menu($nombreMenu), true, true, true, false, true, true);
        }

        // ── Usuario Premium: Directorio + Reportes ─────────────────
        $idPremium    = $tipo('Usuario Premium');
        $menusPremium = ['Directorio', 'Reportes'];
        foreach ($menusPremium as $nombreMenu) {
            $insertar($idPremium, $menu($nombreMenu), true, true, true, false, true, false);
        }

        // ── Usuario Estándar: solo Directorio ──────────────────────
        $idEstandar = $tipo('Usuario Estándar');
        $insertar($idEstandar, $menu('Directorio'), true, true, true, false, false, false);

        // ── Invitado: solo ver Directorio ──────────────────────────
        $idInvitado = $tipo('Invitado');
        $insertar($idInvitado, $menu('Directorio'), true, false, false, false, false, false);
    }
}