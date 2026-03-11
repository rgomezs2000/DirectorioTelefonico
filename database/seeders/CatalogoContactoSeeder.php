<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogoContactoSeeder extends Seeder
{
    public function run(): void
    {
        // ── Tipos de teléfono ─────────────────────────────────────────
        DB::table('tipos_telefono')->insert([
            ['nombre'=>'Celular',     'descripcion'=>'Teléfono móvil personal'],
            ['nombre'=>'Casa',        'descripcion'=>'Teléfono fijo residencial'],
            ['nombre'=>'Trabajo',     'descripcion'=>'Teléfono fijo laboral'],
            ['nombre'=>'Fax',         'descripcion'=>'Número de fax'],
            ['nombre'=>'WhatsApp',    'descripcion'=>'Número con cuenta WhatsApp'],
            ['nombre'=>'Telegram',    'descripcion'=>'Número con cuenta Telegram'],
            ['nombre'=>'Emergencias', 'descripcion'=>'Contacto de emergencia'],
            ['nombre'=>'Otro',        'descripcion'=>'Otro tipo de número'],
        ]);

        // ── Tipos de dirección ────────────────────────────────────────
        DB::table('tipos_direccion')->insert([
            ['nombre'=>'Residencia',      'descripcion'=>'Dirección del hogar'],
            ['nombre'=>'Trabajo',         'descripcion'=>'Dirección laboral'],
            ['nombre'=>'Correspondencia', 'descripcion'=>'Dirección para envíos'],
            ['nombre'=>'Facturación',     'descripcion'=>'Dirección de facturación'],
            ['nombre'=>'Otro',            'descripcion'=>'Otro tipo de dirección'],
        ]);

        // ── Tipos de email ────────────────────────────────────────────
        DB::table('tipos_email')->insert([
            ['nombre'=>'Personal'],
            ['nombre'=>'Trabajo'],
            ['nombre'=>'Académico'],
            ['nombre'=>'Alternativo'],
            ['nombre'=>'Otro'],
        ]);

        // ── Categorías de contacto ────────────────────────────────────
        DB::table('categorias_contacto')->insert([
            ['nombre'=>'Familia',    'color_hex'=>'#FF6B6B'],
            ['nombre'=>'Amigos',     'color_hex'=>'#4ECDC4'],
            ['nombre'=>'Trabajo',    'color_hex'=>'#45B7D1'],
            ['nombre'=>'Negocios',   'color_hex'=>'#96CEB4'],
            ['nombre'=>'Médico',     'color_hex'=>'#FF9999'],
            ['nombre'=>'Educación',  'color_hex'=>'#FFCC02'],
            ['nombre'=>'Emergencias','color_hex'=>'#FF4444'],
            ['nombre'=>'Gobierno',   'color_hex'=>'#6C757D'],
            ['nombre'=>'Proveedor',  'color_hex'=>'#8B4513'],
            ['nombre'=>'Cliente',    'color_hex'=>'#2196F3'],
            ['nombre'=>'Otro',       'color_hex'=>'#9E9E9E'],
        ]);

        // ── Redes sociales ────────────────────────────────────────────
        DB::table('redes_sociales')->insert([
            ['nombre'=>'Facebook',  'url_base'=>'https://facebook.com/'],
            ['nombre'=>'Instagram', 'url_base'=>'https://instagram.com/'],
            ['nombre'=>'Twitter/X', 'url_base'=>'https://x.com/'],
            ['nombre'=>'LinkedIn',  'url_base'=>'https://linkedin.com/in/'],
            ['nombre'=>'TikTok',    'url_base'=>'https://tiktok.com/@'],
            ['nombre'=>'YouTube',   'url_base'=>'https://youtube.com/@'],
            ['nombre'=>'Snapchat',  'url_base'=>'https://snapchat.com/add/'],
            ['nombre'=>'Pinterest', 'url_base'=>'https://pinterest.com/'],
            ['nombre'=>'GitHub',    'url_base'=>'https://github.com/'],
            ['nombre'=>'Telegram',  'url_base'=>'https://t.me/'],
            ['nombre'=>'Otro',      'url_base'=>null],
        ]);
    }
}