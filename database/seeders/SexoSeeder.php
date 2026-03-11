<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SexoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sexos')->insert([
            ['nombre' => 'Masculino',             'abreviatura' => 'M',   'descripcion' => 'Hombre / varón'],
            ['nombre' => 'Femenino',              'abreviatura' => 'F',   'descripcion' => 'Mujer'],
            ['nombre' => 'No binario',            'abreviatura' => 'NB',  'descripcion' => 'Identidad fuera del binarismo'],
            ['nombre' => 'Género fluido',         'abreviatura' => 'GF',  'descripcion' => 'Género que varía con el tiempo'],
            ['nombre' => 'Agénero',               'abreviatura' => 'AG',  'descripcion' => 'Sin identificación de género'],
            ['nombre' => 'Bigénero',              'abreviatura' => 'BG',  'descripcion' => 'Dos géneros simultáneos'],
            ['nombre' => 'Intersexual',           'abreviatura' => 'IS',  'descripcion' => 'Variación biológica de sexo'],
            ['nombre' => 'Prefiero no indicarlo', 'abreviatura' => 'NS',  'descripcion' => 'El usuario prefiere no especificar'],
        ]);
    }
}