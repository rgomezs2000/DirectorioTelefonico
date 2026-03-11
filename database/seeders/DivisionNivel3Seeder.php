<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionNivel3Seeder extends Seeder
{
    public function run(): void
    {
        $get = fn(string $nombre) => DB::table('divisiones_nivel2')
            ->where('nombre', $nombre)->value('id_nivel2');

        // ── Comunas de Medellín ───────────────────────────────────────
        $medellin = $get('Medellín');
        $comunas = [
            ['nombre'=>'El Poblado',        'cp'=>'050022'],
            ['nombre'=>'Laureles-Estadio',  'cp'=>'050034'],
            ['nombre'=>'Belén',             'cp'=>'050036'],
            ['nombre'=>'Robledo',           'cp'=>'050011'],
            ['nombre'=>'Aranjuez',          'cp'=>'050013'],
            ['nombre'=>'La Candelaria',     'cp'=>'050010'],
            ['nombre'=>'Buenos Aires',      'cp'=>'050024'],
            ['nombre'=>'La América',        'cp'=>'050035'],
            ['nombre'=>'Guayabal',          'cp'=>'050037'],
            ['nombre'=>'Villa Hermosa',     'cp'=>'050023'],
            ['nombre'=>'San Javier',        'cp'=>'050016'],
            ['nombre'=>'Castilla',          'cp'=>'050012'],
            ['nombre'=>'Doce de Octubre',   'cp'=>'050014'],
            ['nombre'=>'Manrique',          'cp'=>'050015'],
            ['nombre'=>'Santa Cruz',        'cp'=>'050017'],
            ['nombre'=>'Popular',           'cp'=>'050018'],
        ];
        foreach ($comunas as $c) {
            DB::table('divisiones_nivel3')->insert([
                'id_nivel2'     => $medellin,
                'nombre'        => $c['nombre'],
                'tipo'          => 'Comuna',
                'codigo_postal' => $c['cp'],
            ]);
        }

        // ── Barrios de Cali ───────────────────────────────────────────
        $cali = $get('Cali');
        $barriosCali = [
            ['nombre'=>'Granada',        'cp'=>'760020'],
            ['nombre'=>'San Antonio',    'cp'=>'760001'],
            ['nombre'=>'El Peñón',       'cp'=>'760003'],
            ['nombre'=>'Ciudad Jardín',  'cp'=>'760031'],
            ['nombre'=>'Chapinero',      'cp'=>'760004'],
            ['nombre'=>'Menga',          'cp'=>'760040'],
            ['nombre'=>'Versalles',      'cp'=>'760025'],
            ['nombre'=>'Normandía',      'cp'=>'760035'],
            ['nombre'=>'Teodoro Llorente','cp'=>'760010'],
            ['nombre'=>'Bretaña',        'cp'=>'760007'],
        ];
        foreach ($barriosCali as $b) {
            DB::table('divisiones_nivel3')->insert([
                'id_nivel2'     => $cali,
                'nombre'        => $b['nombre'],
                'tipo'          => 'Barrio',
                'codigo_postal' => $b['cp'],
            ]);
        }

        // ── Barrios de Bogotá ─────────────────────────────────────────
        $bogota = $get('Bogotá D.C.');
        $barriosBog = [
            ['nombre'=>'Chapinero',         'cp'=>'110221'],
            ['nombre'=>'Usaquén',           'cp'=>'110111'],
            ['nombre'=>'Santa Fe',          'cp'=>'110311'],
            ['nombre'=>'La Candelaria',     'cp'=>'110411'],
            ['nombre'=>'San Cristóbal',     'cp'=>'110511'],
            ['nombre'=>'Usme',              'cp'=>'110611'],
            ['nombre'=>'Tunjuelito',        'cp'=>'110711'],
            ['nombre'=>'Bosa',              'cp'=>'110811'],
            ['nombre'=>'Kennedy',           'cp'=>'110911'],
            ['nombre'=>'Fontibón',          'cp'=>'111011'],
            ['nombre'=>'Engativá',          'cp'=>'111111'],
            ['nombre'=>'Suba',              'cp'=>'111211'],
        ];
        foreach ($barriosBog as $b) {
            DB::table('divisiones_nivel3')->insert([
                'id_nivel2'     => $bogota,
                'nombre'        => $b['nombre'],
                'tipo'          => 'Localidad',
                'codigo_postal' => $b['cp'],
            ]);
        }

        // ── Colonias de Guadalajara ───────────────────────────────────
        $gdl = $get('Guadalajara');
        $coloniasGdl = [
            ['nombre'=>'Chapalita',          'cp'=>'45040'],
            ['nombre'=>'Providencia',        'cp'=>'44630'],
            ['nombre'=>'Americana',          'cp'=>'44160'],
            ['nombre'=>'Lafontaine',         'cp'=>'44130'],
            ['nombre'=>'Arcos Vallarta',     'cp'=>'44130'],
            ['nombre'=>'Jardines del Bosque','cp'=>'44520'],
            ['nombre'=>'Huentitán el Alto',  'cp'=>'44290'],
        ];
        foreach ($coloniasGdl as $col) {
            DB::table('divisiones_nivel3')->insert([
                'id_nivel2'     => $gdl,
                'nombre'        => $col['nombre'],
                'tipo'          => 'Colonia',
                'codigo_postal' => $col['cp'],
            ]);
        }
    }
}