<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // ── Sección 1: Catálogos base ──────────────────────────
            SexoSeeder::class,
            TipoUsuarioSeeder::class,
            ProfesionSeeder::class,

            // ── Sección 2: Geografía ───────────────────────────────
            PaisSeeder::class,
            DivisionNivel1Seeder::class,
            DivisionNivel2Seeder::class,
            DivisionNivel3Seeder::class,

            // ── Sección 3: Usuarios ────────────────────────────────
            UsuarioAdminSeeder::class,

            // ── Sección 4: Directorio ──────────────────────────────
            CatalogoContactoSeeder::class,

            // ── Sección 6: Navegación (orden obligatorio) ──────────
            IconoSeeder::class,     // 1° – sin dependencias
            MenuSeeder::class,      // 2° – depende de iconos
            SubmenuSeeder::class,   // 3° – depende de menus + iconos
            ModuloSeeder::class,    // 4° – depende de submenus
            PermisoSeeder::class,   // 5° – depende de tipos_usuario + menus
        ]);
    }
}