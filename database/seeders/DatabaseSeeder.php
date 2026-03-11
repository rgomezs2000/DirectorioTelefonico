<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // ── 1. Catálogos base (sin dependencias) ──────────────────
            SexoSeeder::class,
            TipoUsuarioSeeder::class,
            ProfesionSeeder::class,

            // ── 2. Geografía (en orden de dependencia) ─────────────────
            PaisSeeder::class,
            DivisionNivel1Seeder::class,
            DivisionNivel2Seeder::class,
            DivisionNivel3Seeder::class,

            // ── 3. Catálogos de contacto ───────────────────────────────
            CatalogoContactoSeeder::class,

            // ── 4. Usuarios y datos de prueba ──────────────────────────
            UsuarioAdminSeeder::class,
        ]);
    }
}