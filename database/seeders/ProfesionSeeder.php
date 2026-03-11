<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfesionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('profesiones')->insert([
            ['nombre' => 'Médico / Médica',              'categoria' => 'Salud'],
            ['nombre' => 'Enfermero / Enfermera',        'categoria' => 'Salud'],
            ['nombre' => 'Odontólogo / Odontóloga',      'categoria' => 'Salud'],
            ['nombre' => 'Psicólogo / Psicóloga',        'categoria' => 'Salud'],
            ['nombre' => 'Farmacéutico / Farmacéutica',  'categoria' => 'Salud'],
            ['nombre' => 'Ingeniero de Software',        'categoria' => 'Tecnología'],
            ['nombre' => 'Desarrollador Web',            'categoria' => 'Tecnología'],
            ['nombre' => 'Analista de Datos',            'categoria' => 'Tecnología'],
            ['nombre' => 'Administrador de Redes',       'categoria' => 'Tecnología'],
            ['nombre' => 'Diseñador UX/UI',              'categoria' => 'Tecnología'],
            ['nombre' => 'Docente / Profesor',           'categoria' => 'Educación'],
            ['nombre' => 'Investigador / Investigadora', 'categoria' => 'Educación'],
            ['nombre' => 'Rector / Rectora',             'categoria' => 'Educación'],
            ['nombre' => 'Abogado / Abogada',            'categoria' => 'Derecho'],
            ['nombre' => 'Juez / Jueza',                 'categoria' => 'Derecho'],
            ['nombre' => 'Notario / Notaria',            'categoria' => 'Derecho'],
            ['nombre' => 'Contador / Contadora',         'categoria' => 'Finanzas'],
            ['nombre' => 'Economista',                   'categoria' => 'Finanzas'],
            ['nombre' => 'Asesor Financiero',            'categoria' => 'Finanzas'],
            ['nombre' => 'Arquitecto / Arquitecta',      'categoria' => 'Construcción'],
            ['nombre' => 'Ingeniero Civil',              'categoria' => 'Construcción'],
            ['nombre' => 'Electricista',                 'categoria' => 'Construcción'],
            ['nombre' => 'Periodista',                   'categoria' => 'Comunicación'],
            ['nombre' => 'Locutor / Locutora',           'categoria' => 'Comunicación'],
            ['nombre' => 'Fotógrafo / Fotógrafa',        'categoria' => 'Arte y Diseño'],
            ['nombre' => 'Artista Plástico',             'categoria' => 'Arte y Diseño'],
            ['nombre' => 'Músico / Música',              'categoria' => 'Arte y Diseño'],
            ['nombre' => 'Chef / Cocinero Profesional',  'categoria' => 'Gastronomía'],
            ['nombre' => 'Nutricionista',                'categoria' => 'Gastronomía'],
            ['nombre' => 'Agricultor / Agricultora',     'categoria' => 'Agropecuario'],
            ['nombre' => 'Veterinario / Veterinaria',    'categoria' => 'Agropecuario'],
            ['nombre' => 'Militar',                      'categoria' => 'Seguridad'],
            ['nombre' => 'Policía',                      'categoria' => 'Seguridad'],
            ['nombre' => 'Bombero / Bombera',            'categoria' => 'Seguridad'],
            ['nombre' => 'Empresario / Empresaria',      'categoria' => 'Negocios'],
            ['nombre' => 'Comerciante',                  'categoria' => 'Negocios'],
            ['nombre' => 'Estudiante',                   'categoria' => 'Educación'],
            ['nombre' => 'Ama / Amo de casa',            'categoria' => 'Hogar'],
            ['nombre' => 'Jubilado / Jubilada',          'categoria' => 'Otro'],
            ['nombre' => 'Otro / No especificado',       'categoria' => 'Otro'],
        ]);
    }
}