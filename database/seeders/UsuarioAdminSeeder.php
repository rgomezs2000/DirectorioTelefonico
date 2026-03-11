<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuarioAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener IDs de catálogos
        $superadmin  = DB::table('tipos_usuario')->where('nombre', 'Superadministrador')->value('id_tipo_usuario');
        $masculino   = DB::table('sexos')->where('abreviatura', 'M')->value('id_sexo');
        $colombia    = DB::table('paises')->where('iso2', 'CO')->value('id_pais');
        $antioquia   = DB::table('divisiones_nivel1')->where('codigo', 'CO-ANT')->value('id_nivel1');
        $medellin    = DB::table('divisiones_nivel2')->where('codigo', '05001')->value('id_nivel2');
        $poblado     = DB::table('divisiones_nivel3')->where('nombre', 'El Poblado')->value('id_nivel3');

        // ── Superadministrador ────────────────────────────────────────
        $adminId = DB::table('usuarios')->insertGetId([
            'id_tipo_usuario' => $superadmin,
            'id_sexo'         => $masculino,
            'id_pais'         => $colombia,
            'id_nivel1'       => $antioquia,
            'id_nivel2'       => $medellin,
            'id_nivel3'       => $poblado,
            'username'        => 'admin',
            'email'           => 'admin@directorio.test',
            'nombres'         => 'Administrador',
            'apellidos'       => 'General',
            'activo'          => true,
            'email_verificado'=> true,
        ]);

        DB::table('credenciales')->insert([
            'id_usuario'             => $adminId,
            'password_hash'          => Hash::make('Admin@12345!'),
            'algoritmo'              => 'bcrypt',
            'token_activacion_usado' => true,   // ya activado
            'ultimo_cambio_pass'     => now(),
        ]);

        // ── Usuario de prueba estándar ────────────────────────────────
        $estandar = DB::table('tipos_usuario')->where('nombre', 'Usuario Estándar')->value('id_tipo_usuario');
        $femenino = DB::table('sexos')->where('abreviatura', 'F')->value('id_sexo');

        $userId = DB::table('usuarios')->insertGetId([
            'id_tipo_usuario'  => $estandar,
            'id_sexo'          => $femenino,
            'id_pais'          => $colombia,
            'id_nivel1'        => $antioquia,
            'id_nivel2'        => $medellin,
            'username'         => 'maria.garcia',
            'email'            => 'maria@directorio.test',
            'nombres'          => 'María',
            'apellidos'        => 'García López',
            'fecha_nacimiento' => '1990-05-15',
            'activo'           => true,
            'email_verificado' => false,
        ]);

        // Token de activación pendiente
        $tokenRaw = Str::random(64);
        DB::table('credenciales')->insert([
            'id_usuario'           => $userId,
            'password_hash'        => Hash::make('Usuario@2024!'),
            'algoritmo'            => 'bcrypt',
            'token_activacion'     => hash('sha256', $tokenRaw),
            'token_activacion_exp' => now()->addHours(48),
        ]);

        // Contacto de prueba para el usuario admin
        $contactoId = DB::table('contactos')->insertGetId([
            'id_usuario'  => $adminId,
            'id_sexo'     => $masculino,
            'id_pais'     => $colombia,
            'id_nivel1'   => $antioquia,
            'id_nivel2'   => $medellin,
            'nombres'     => 'Carlos',
            'apellidos'   => 'Ramírez Pérez',
            'empresa'     => 'Tech Solutions S.A.S',
            'cargo'       => 'Gerente de TI',
            'favorito'    => true,
        ]);

        DB::table('telefonos_contacto')->insert([
            [
                'id_contacto'      => $contactoId,
                'id_tipo_telefono' => DB::table('tipos_telefono')->where('nombre','Celular')->value('id_tipo_telefono'),
                'id_pais'          => $colombia,
                'numero'           => '3001234567',
                'es_principal'     => true,
            ],
            [
                'id_contacto'      => $contactoId,
                'id_tipo_telefono' => DB::table('tipos_telefono')->where('nombre','WhatsApp')->value('id_tipo_telefono'),
                'id_pais'          => $colombia,
                'numero'           => '3001234567',
                'es_principal'     => false,
            ],
        ]);

        DB::table('emails_contacto')->insert([
            'id_contacto'   => $contactoId,
            'id_tipo_email' => DB::table('tipos_email')->where('nombre','Trabajo')->value('id_tipo_email'),
            'email'         => 'carlos@techsolutions.co',
            'es_principal'  => true,
        ]);
    }
}