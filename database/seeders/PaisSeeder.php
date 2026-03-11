<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaisSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('paises')->insert([
            ['nombre'=>'Argentina',           'iso2'=>'AR','iso3'=>'ARG','codigo_numerico'=>'032','codigo_telefono'=>'+54',  'continente'=>'América del Sur',  'capital'=>'Buenos Aires',         'moneda'=>'ARS','idioma_oficial'=>'Español'],
            ['nombre'=>'Bolivia',             'iso2'=>'BO','iso3'=>'BOL','codigo_numerico'=>'068','codigo_telefono'=>'+591', 'continente'=>'América del Sur',  'capital'=>'Sucre / La Paz',       'moneda'=>'BOB','idioma_oficial'=>'Español'],
            ['nombre'=>'Brasil',              'iso2'=>'BR','iso3'=>'BRA','codigo_numerico'=>'076','codigo_telefono'=>'+55',  'continente'=>'América del Sur',  'capital'=>'Brasilia',             'moneda'=>'BRL','idioma_oficial'=>'Portugués'],
            ['nombre'=>'Chile',               'iso2'=>'CL','iso3'=>'CHL','codigo_numerico'=>'152','codigo_telefono'=>'+56',  'continente'=>'América del Sur',  'capital'=>'Santiago',             'moneda'=>'CLP','idioma_oficial'=>'Español'],
            ['nombre'=>'Colombia',            'iso2'=>'CO','iso3'=>'COL','codigo_numerico'=>'170','codigo_telefono'=>'+57',  'continente'=>'América del Sur',  'capital'=>'Bogotá',               'moneda'=>'COP','idioma_oficial'=>'Español'],
            ['nombre'=>'Costa Rica',          'iso2'=>'CR','iso3'=>'CRI','codigo_numerico'=>'188','codigo_telefono'=>'+506', 'continente'=>'América Central',  'capital'=>'San José',             'moneda'=>'CRC','idioma_oficial'=>'Español'],
            ['nombre'=>'Cuba',                'iso2'=>'CU','iso3'=>'CUB','codigo_numerico'=>'192','codigo_telefono'=>'+53',  'continente'=>'América del Norte','capital'=>'La Habana',            'moneda'=>'CUP','idioma_oficial'=>'Español'],
            ['nombre'=>'Ecuador',             'iso2'=>'EC','iso3'=>'ECU','codigo_numerico'=>'218','codigo_telefono'=>'+593', 'continente'=>'América del Sur',  'capital'=>'Quito',                'moneda'=>'USD','idioma_oficial'=>'Español'],
            ['nombre'=>'El Salvador',         'iso2'=>'SV','iso3'=>'SLV','codigo_numerico'=>'222','codigo_telefono'=>'+503', 'continente'=>'América Central',  'capital'=>'San Salvador',         'moneda'=>'USD','idioma_oficial'=>'Español'],
            ['nombre'=>'España',              'iso2'=>'ES','iso3'=>'ESP','codigo_numerico'=>'724','codigo_telefono'=>'+34',  'continente'=>'Europa',           'capital'=>'Madrid',               'moneda'=>'EUR','idioma_oficial'=>'Español'],
            ['nombre'=>'Estados Unidos',      'iso2'=>'US','iso3'=>'USA','codigo_numerico'=>'840','codigo_telefono'=>'+1',   'continente'=>'América del Norte','capital'=>'Washington D.C.',      'moneda'=>'USD','idioma_oficial'=>'Inglés'],
            ['nombre'=>'Guatemala',           'iso2'=>'GT','iso3'=>'GTM','codigo_numerico'=>'320','codigo_telefono'=>'+502', 'continente'=>'América Central',  'capital'=>'Ciudad de Guatemala',  'moneda'=>'GTQ','idioma_oficial'=>'Español'],
            ['nombre'=>'Honduras',            'iso2'=>'HN','iso3'=>'HND','codigo_numerico'=>'340','codigo_telefono'=>'+504', 'continente'=>'América Central',  'capital'=>'Tegucigalpa',          'moneda'=>'HNL','idioma_oficial'=>'Español'],
            ['nombre'=>'México',              'iso2'=>'MX','iso3'=>'MEX','codigo_numerico'=>'484','codigo_telefono'=>'+52',  'continente'=>'América del Norte','capital'=>'Ciudad de México',     'moneda'=>'MXN','idioma_oficial'=>'Español'],
            ['nombre'=>'Nicaragua',           'iso2'=>'NI','iso3'=>'NIC','codigo_numerico'=>'558','codigo_telefono'=>'+505', 'continente'=>'América Central',  'capital'=>'Managua',              'moneda'=>'NIO','idioma_oficial'=>'Español'],
            ['nombre'=>'Panamá',              'iso2'=>'PA','iso3'=>'PAN','codigo_numerico'=>'591','codigo_telefono'=>'+507', 'continente'=>'América Central',  'capital'=>'Ciudad de Panamá',     'moneda'=>'PAB','idioma_oficial'=>'Español'],
            ['nombre'=>'Paraguay',            'iso2'=>'PY','iso3'=>'PRY','codigo_numerico'=>'600','codigo_telefono'=>'+595', 'continente'=>'América del Sur',  'capital'=>'Asunción',             'moneda'=>'PYG','idioma_oficial'=>'Español'],
            ['nombre'=>'Perú',                'iso2'=>'PE','iso3'=>'PER','codigo_numerico'=>'604','codigo_telefono'=>'+51',  'continente'=>'América del Sur',  'capital'=>'Lima',                 'moneda'=>'PEN','idioma_oficial'=>'Español'],
            ['nombre'=>'Puerto Rico',         'iso2'=>'PR','iso3'=>'PRI','codigo_numerico'=>'630','codigo_telefono'=>'+1787','continente'=>'América del Norte','capital'=>'San Juan',             'moneda'=>'USD','idioma_oficial'=>'Español'],
            ['nombre'=>'República Dominicana','iso2'=>'DO','iso3'=>'DOM','codigo_numerico'=>'214','codigo_telefono'=>'+1809','continente'=>'América del Norte','capital'=>'Santo Domingo',        'moneda'=>'DOP','idioma_oficial'=>'Español'],
            ['nombre'=>'Uruguay',             'iso2'=>'UY','iso3'=>'URY','codigo_numerico'=>'858','codigo_telefono'=>'+598', 'continente'=>'América del Sur',  'capital'=>'Montevideo',           'moneda'=>'UYU','idioma_oficial'=>'Español'],
            ['nombre'=>'Venezuela',           'iso2'=>'VE','iso3'=>'VEN','codigo_numerico'=>'862','codigo_telefono'=>'+58',  'continente'=>'América del Sur',  'capital'=>'Caracas',              'moneda'=>'VES','idioma_oficial'=>'Español'],
            ['nombre'=>'Alemania',            'iso2'=>'DE','iso3'=>'DEU','codigo_numerico'=>'276','codigo_telefono'=>'+49',  'continente'=>'Europa',           'capital'=>'Berlín',               'moneda'=>'EUR','idioma_oficial'=>'Alemán'],
            ['nombre'=>'Francia',             'iso2'=>'FR','iso3'=>'FRA','codigo_numerico'=>'250','codigo_telefono'=>'+33',  'continente'=>'Europa',           'capital'=>'París',                'moneda'=>'EUR','idioma_oficial'=>'Francés'],
            ['nombre'=>'Italia',              'iso2'=>'IT','iso3'=>'ITA','codigo_numerico'=>'380','codigo_telefono'=>'+39',  'continente'=>'Europa',           'capital'=>'Roma',                 'moneda'=>'EUR','idioma_oficial'=>'Italiano'],
            ['nombre'=>'Canadá',              'iso2'=>'CA','iso3'=>'CAN','codigo_numerico'=>'124','codigo_telefono'=>'+1',   'continente'=>'América del Norte','capital'=>'Ottawa',               'moneda'=>'CAD','idioma_oficial'=>'Inglés/Francés'],
            ['nombre'=>'China',               'iso2'=>'CN','iso3'=>'CHN','codigo_numerico'=>'156','codigo_telefono'=>'+86',  'continente'=>'Asia',             'capital'=>'Pekín',                'moneda'=>'CNY','idioma_oficial'=>'Chino mandarín'],
            ['nombre'=>'Japón',               'iso2'=>'JP','iso3'=>'JPN','codigo_numerico'=>'392','codigo_telefono'=>'+81',  'continente'=>'Asia',             'capital'=>'Tokio',                'moneda'=>'JPY','idioma_oficial'=>'Japonés'],
            ['nombre'=>'Reino Unido',         'iso2'=>'GB','iso3'=>'GBR','codigo_numerico'=>'826','codigo_telefono'=>'+44',  'continente'=>'Europa',           'capital'=>'Londres',              'moneda'=>'GBP','idioma_oficial'=>'Inglés'],
            ['nombre'=>'Portugal',            'iso2'=>'PT','iso3'=>'PRT','codigo_numerico'=>'620','codigo_telefono'=>'+351', 'continente'=>'Europa',           'capital'=>'Lisboa',               'moneda'=>'EUR','idioma_oficial'=>'Portugués'],
        ]);
    }
}