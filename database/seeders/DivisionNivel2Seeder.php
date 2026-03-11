<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionNivel2Seeder extends Seeder
{
    public function run(): void
    {
        $get = fn(string $nombre) => DB::table('divisiones_nivel1')
            ->where('nombre', $nombre)->value('id_nivel1');

        // ── Municipios de Antioquia ───────────────────────────────────
        $antioquia = $get('Antioquia');
        $municipiosAnt = [
            ['codigo'=>'05001','nombre'=>'Medellín'],
            ['codigo'=>'05088','nombre'=>'Bello'],
            ['codigo'=>'05360','nombre'=>'Itagüí'],
            ['codigo'=>'05266','nombre'=>'Envigado'],
            ['codigo'=>'05045','nombre'=>'Apartadó'],
            ['codigo'=>'05837','nombre'=>'Turbo'],
            ['codigo'=>'05615','nombre'=>'Rionegro'],
            ['codigo'=>'05154','nombre'=>'Caucasia'],
            ['codigo'=>'05631','nombre'=>'Sabaneta'],
            ['codigo'=>'05212','nombre'=>'Copacabana'],
        ];
        foreach ($municipiosAnt as $m) {
            DB::table('divisiones_nivel2')->insert([
                'id_nivel1' => $antioquia,
                'nombre'    => $m['nombre'],
                'codigo'    => $m['codigo'],
                'tipo'      => 'Municipio',
            ]);
        }

        // ── Municipios de Valle del Cauca ─────────────────────────────
        $valle = $get('Valle del Cauca');
        $municipiosValle = [
            ['codigo'=>'76001','nombre'=>'Cali'],
            ['codigo'=>'76109','nombre'=>'Buenaventura'],
            ['codigo'=>'76520','nombre'=>'Palmira'],
            ['codigo'=>'76834','nombre'=>'Tuluá'],
            ['codigo'=>'76147','nombre'=>'Cartago'],
            ['codigo'=>'76111','nombre'=>'Buga'],
            ['codigo'=>'76364','nombre'=>'Jamundí'],
            ['codigo'=>'76892','nombre'=>'Yumbo'],
        ];
        foreach ($municipiosValle as $m) {
            DB::table('divisiones_nivel2')->insert([
                'id_nivel1' => $valle,
                'nombre'    => $m['nombre'],
                'codigo'    => $m['codigo'],
                'tipo'      => 'Municipio',
            ]);
        }

        // ── Municipios de Cundinamarca / Bogotá ───────────────────────
        $cundi = $get('Cundinamarca');
        $municipiosCundi = [
            ['codigo'=>'25001','nombre'=>'Agua de Dios'],
            ['codigo'=>'25019','nombre'=>'Albán'],
            ['codigo'=>'25035','nombre'=>'Anapoima'],
            ['codigo'=>'25053','nombre'=>'Arbeláez'],
            ['codigo'=>'25086','nombre'=>'Beltrán'],
            ['codigo'=>'25095','nombre'=>'Bituima'],
            ['codigo'=>'25099','nombre'=>'Bojacá'],
            ['codigo'=>'25120','nombre'=>'Cabrera'],
            ['codigo'=>'25123','nombre'=>'Cachipay'],
            ['codigo'=>'25126','nombre'=>'Cajicá'],
        ];
        foreach ($municipiosCundi as $m) {
            DB::table('divisiones_nivel2')->insert([
                'id_nivel1' => $cundi,
                'nombre'    => $m['nombre'],
                'codigo'    => $m['codigo'],
                'tipo'      => 'Municipio',
            ]);
        }

        // Bogotá D.C. como municipio de su propio departamento
        $bogotaDep = $get('Bogotá D.C.');
        DB::table('divisiones_nivel2')->insert([
            'id_nivel1' => $bogotaDep,
            'nombre'    => 'Bogotá D.C.',
            'codigo'    => '11001',
            'tipo'      => 'Distrito Capital',
        ]);

        // ── Municipios de Jalisco (México) ────────────────────────────
        $jalisco = $get('Jalisco');
        $municipiosJal = [
            ['codigo'=>'14039','nombre'=>'Guadalajara'],
            ['codigo'=>'14120','nombre'=>'Zapopan'],
            ['codigo'=>'14097','nombre'=>'Tlaquepaque'],
            ['codigo'=>'14101','nombre'=>'Tonalá'],
            ['codigo'=>'14067','nombre'=>'Puerto Vallarta'],
            ['codigo'=>'14051','nombre'=>'Lagos de Moreno'],
            ['codigo'=>'14089','nombre'=>'Tepatitlán'],
        ];
        foreach ($municipiosJal as $m) {
            DB::table('divisiones_nivel2')->insert([
                'id_nivel1' => $jalisco,
                'nombre'    => $m['nombre'],
                'codigo'    => $m['codigo'],
                'tipo'      => 'Municipio',
            ]);
        }

        // ── Municipios de Nuevo León (México) ─────────────────────────
        $nuevoLeon = $get('Nuevo León');
        $municipiosNL = [
            ['codigo'=>'19039','nombre'=>'Monterrey'],
            ['codigo'=>'19006','nombre'=>'San Nicolás de los Garza'],
            ['codigo'=>'19018','nombre'=>'Guadalupe'],
            ['codigo'=>'19021','nombre'=>'Apodaca'],
            ['codigo'=>'19047','nombre'=>'San Pedro Garza García'],
            ['codigo'=>'19010','nombre'=>'Santa Catarina'],
        ];
        foreach ($municipiosNL as $m) {
            DB::table('divisiones_nivel2')->insert([
                'id_nivel1' => $nuevoLeon,
                'nombre'    => $m['nombre'],
                'codigo'    => $m['codigo'],
                'tipo'      => 'Municipio',
            ]);
        }
    }
}