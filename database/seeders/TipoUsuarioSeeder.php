<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoUsuarioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tipos_usuario')->insert([
            ['nombre' => 'Superadministrador', 'descripcion' => 'Control total del sistema',             'nivel_acceso' => 100],
            ['nombre' => 'Administrador',      'descripcion' => 'Gestión de usuarios y contenido',       'nivel_acceso' => 80],
            ['nombre' => 'Moderador',          'descripcion' => 'Revisión y aprobación de contenido',    'nivel_acceso' => 60],
            ['nombre' => 'Usuario Premium',    'descripcion' => 'Acceso extendido con funciones extra',  'nivel_acceso' => 40],
            ['nombre' => 'Usuario Estándar',   'descripcion' => 'Acceso básico al directorio',           'nivel_acceso' => 20],
            ['nombre' => 'Invitado',           'descripcion' => 'Solo lectura, sin autenticación',       'nivel_acceso' => 5],
        ]);
    }
}