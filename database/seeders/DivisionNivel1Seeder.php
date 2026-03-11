<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionNivel1Seeder extends Seeder
{
    public function run(): void
    {
        $colombia = DB::table('paises')->where('iso2', 'CO')->value('id_pais');
        $mexico   = DB::table('paises')->where('iso2', 'MX')->value('id_pais');

        // ── Departamentos de Colombia ─────────────────────────────────
        $departamentos = [
            ['codigo'=>'CO-AMA','nombre'=>'Amazonas',              'capital'=>'Leticia'],
            ['codigo'=>'CO-ANT','nombre'=>'Antioquia',             'capital'=>'Medellín'],
            ['codigo'=>'CO-ARA','nombre'=>'Arauca',                'capital'=>'Arauca'],
            ['codigo'=>'CO-ATL','nombre'=>'Atlántico',             'capital'=>'Barranquilla'],
            ['codigo'=>'CO-BOL','nombre'=>'Bolívar',               'capital'=>'Cartagena de Indias'],
            ['codigo'=>'CO-BOY','nombre'=>'Boyacá',                'capital'=>'Tunja'],
            ['codigo'=>'CO-CAL','nombre'=>'Caldas',                'capital'=>'Manizales'],
            ['codigo'=>'CO-CAQ','nombre'=>'Caquetá',               'capital'=>'Florencia'],
            ['codigo'=>'CO-CAS','nombre'=>'Casanare',              'capital'=>'Yopal'],
            ['codigo'=>'CO-CAU','nombre'=>'Cauca',                 'capital'=>'Popayán'],
            ['codigo'=>'CO-CES','nombre'=>'Cesar',                 'capital'=>'Valledupar'],
            ['codigo'=>'CO-CHO','nombre'=>'Chocó',                 'capital'=>'Quibdó'],
            ['codigo'=>'CO-COR','nombre'=>'Córdoba',               'capital'=>'Montería'],
            ['codigo'=>'CO-CUN','nombre'=>'Cundinamarca',          'capital'=>'Bogotá D.C.'],
            ['codigo'=>'CO-DC', 'nombre'=>'Bogotá D.C.',           'capital'=>'Bogotá D.C.'],
            ['codigo'=>'CO-GUA','nombre'=>'Guainía',               'capital'=>'Inírida'],
            ['codigo'=>'CO-GUV','nombre'=>'Guaviare',              'capital'=>'San José del Guaviare'],
            ['codigo'=>'CO-HUI','nombre'=>'Huila',                 'capital'=>'Neiva'],
            ['codigo'=>'CO-LAG','nombre'=>'La Guajira',            'capital'=>'Riohacha'],
            ['codigo'=>'CO-MAG','nombre'=>'Magdalena',             'capital'=>'Santa Marta'],
            ['codigo'=>'CO-MET','nombre'=>'Meta',                  'capital'=>'Villavicencio'],
            ['codigo'=>'CO-NAR','nombre'=>'Nariño',                'capital'=>'Pasto'],
            ['codigo'=>'CO-NSA','nombre'=>'Norte de Santander',    'capital'=>'Cúcuta'],
            ['codigo'=>'CO-PUT','nombre'=>'Putumayo',              'capital'=>'Mocoa'],
            ['codigo'=>'CO-QUI','nombre'=>'Quindío',               'capital'=>'Armenia'],
            ['codigo'=>'CO-RIS','nombre'=>'Risaralda',             'capital'=>'Pereira'],
            ['codigo'=>'CO-SAP','nombre'=>'San Andrés y Providencia','capital'=>'San Andrés'],
            ['codigo'=>'CO-SAN','nombre'=>'Santander',             'capital'=>'Bucaramanga'],
            ['codigo'=>'CO-SUC','nombre'=>'Sucre',                 'capital'=>'Sincelejo'],
            ['codigo'=>'CO-TOL','nombre'=>'Tolima',                'capital'=>'Ibagué'],
            ['codigo'=>'CO-VAC','nombre'=>'Valle del Cauca',       'capital'=>'Cali'],
            ['codigo'=>'CO-VAU','nombre'=>'Vaupés',                'capital'=>'Mitú'],
            ['codigo'=>'CO-VID','nombre'=>'Vichada',               'capital'=>'Puerto Carreño'],
        ];

        foreach ($departamentos as $dep) {
            DB::table('divisiones_nivel1')->insert([
                'id_pais'  => $colombia,
                'nombre'   => $dep['nombre'],
                'codigo'   => $dep['codigo'],
                'tipo'     => 'Departamento',
                'capital'  => $dep['capital'],
            ]);
        }

        // ── Estados de México ─────────────────────────────────────────
        $estados = [
            ['codigo'=>'MX-AGU','nombre'=>'Aguascalientes',     'capital'=>'Aguascalientes'],
            ['codigo'=>'MX-BCN','nombre'=>'Baja California',    'capital'=>'Mexicali'],
            ['codigo'=>'MX-BCS','nombre'=>'Baja California Sur','capital'=>'La Paz'],
            ['codigo'=>'MX-CAM','nombre'=>'Campeche',           'capital'=>'Campeche'],
            ['codigo'=>'MX-CHP','nombre'=>'Chiapas',            'capital'=>'Tuxtla Gutiérrez'],
            ['codigo'=>'MX-CHH','nombre'=>'Chihuahua',          'capital'=>'Chihuahua'],
            ['codigo'=>'MX-CMX','nombre'=>'Ciudad de México',   'capital'=>'Ciudad de México'],
            ['codigo'=>'MX-COA','nombre'=>'Coahuila',           'capital'=>'Saltillo'],
            ['codigo'=>'MX-COL','nombre'=>'Colima',             'capital'=>'Colima'],
            ['codigo'=>'MX-DUR','nombre'=>'Durango',            'capital'=>'Durango'],
            ['codigo'=>'MX-GUA','nombre'=>'Guanajuato',         'capital'=>'Guanajuato'],
            ['codigo'=>'MX-GRO','nombre'=>'Guerrero',           'capital'=>'Chilpancingo'],
            ['codigo'=>'MX-HID','nombre'=>'Hidalgo',            'capital'=>'Pachuca'],
            ['codigo'=>'MX-JAL','nombre'=>'Jalisco',            'capital'=>'Guadalajara'],
            ['codigo'=>'MX-MEX','nombre'=>'México',             'capital'=>'Toluca'],
            ['codigo'=>'MX-MIC','nombre'=>'Michoacán',          'capital'=>'Morelia'],
            ['codigo'=>'MX-MOR','nombre'=>'Morelos',            'capital'=>'Cuernavaca'],
            ['codigo'=>'MX-NAY','nombre'=>'Nayarit',            'capital'=>'Tepic'],
            ['codigo'=>'MX-NLE','nombre'=>'Nuevo León',         'capital'=>'Monterrey'],
            ['codigo'=>'MX-OAX','nombre'=>'Oaxaca',             'capital'=>'Oaxaca de Juárez'],
            ['codigo'=>'MX-PUE','nombre'=>'Puebla',             'capital'=>'Puebla de Zaragoza'],
            ['codigo'=>'MX-QUE','nombre'=>'Querétaro',          'capital'=>'Querétaro'],
            ['codigo'=>'MX-ROO','nombre'=>'Quintana Roo',       'capital'=>'Chetumal'],
            ['codigo'=>'MX-SLP','nombre'=>'San Luis Potosí',    'capital'=>'San Luis Potosí'],
            ['codigo'=>'MX-SIN','nombre'=>'Sinaloa',            'capital'=>'Culiacán'],
            ['codigo'=>'MX-SON','nombre'=>'Sonora',             'capital'=>'Hermosillo'],
            ['codigo'=>'MX-TAB','nombre'=>'Tabasco',            'capital'=>'Villahermosa'],
            ['codigo'=>'MX-TAM','nombre'=>'Tamaulipas',         'capital'=>'Ciudad Victoria'],
            ['codigo'=>'MX-TLA','nombre'=>'Tlaxcala',           'capital'=>'Tlaxcala'],
            ['codigo'=>'MX-VER','nombre'=>'Veracruz',           'capital'=>'Xalapa'],
            ['codigo'=>'MX-YUC','nombre'=>'Yucatán',            'capital'=>'Mérida'],
            ['codigo'=>'MX-ZAC','nombre'=>'Zacatecas',          'capital'=>'Zacatecas'],
        ];

        foreach ($estados as $est) {
            DB::table('divisiones_nivel1')->insert([
                'id_pais' => $mexico,
                'nombre'  => $est['nombre'],
                'codigo'  => $est['codigo'],
                'tipo'    => 'Estado',
                'capital' => $est['capital'],
            ]);
        }
    }
}