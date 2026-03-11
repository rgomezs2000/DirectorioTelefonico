<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IconoSeeder extends Seeder
{
    public function run(): void
    {
        $iconos = [
            // ── Menús principales ──────────────────────────────────
            ['nombre' => 'cog-6-tooth',              'variante' => 'outline', 'componente' => 'heroicon-o-cog-6-tooth',              'clase_css' => 'w-5 h-5'],
            ['nombre' => 'list-bullet',              'variante' => 'outline', 'componente' => 'heroicon-o-list-bullet',              'clase_css' => 'w-5 h-5'],
            ['nombre' => 'book-open',                'variante' => 'outline', 'componente' => 'heroicon-o-book-open',                'clase_css' => 'w-5 h-5'],
            ['nombre' => 'chart-bar',                'variante' => 'outline', 'componente' => 'heroicon-o-chart-bar',                'clase_css' => 'w-5 h-5'],
            ['nombre' => 'arrow-right-on-rectangle', 'variante' => 'outline', 'componente' => 'heroicon-o-arrow-right-on-rectangle', 'clase_css' => 'w-5 h-5'],
            // ── Administración ─────────────────────────────────────
            ['nombre' => 'users',                    'variante' => 'outline', 'componente' => 'heroicon-o-users',                    'clase_css' => 'w-5 h-5'],
            ['nombre' => 'user-circle',              'variante' => 'outline', 'componente' => 'heroicon-o-user-circle',              'clase_css' => 'w-5 h-5'],
            ['nombre' => 'shield-check',             'variante' => 'outline', 'componente' => 'heroicon-o-shield-check',             'clase_css' => 'w-5 h-5'],
            ['nombre' => 'squares-2x2',              'variante' => 'outline', 'componente' => 'heroicon-o-squares-2x2',              'clase_css' => 'w-5 h-5'],
            ['nombre' => 'clipboard-document-list',  'variante' => 'outline', 'componente' => 'heroicon-o-clipboard-document-list',  'clase_css' => 'w-5 h-5'],
            // ── Maestros / Geografía ───────────────────────────────
            ['nombre' => 'globe-alt',                'variante' => 'outline', 'componente' => 'heroicon-o-globe-alt',                'clase_css' => 'w-5 h-5'],
            ['nombre' => 'map-pin',                  'variante' => 'outline', 'componente' => 'heroicon-o-map-pin',                  'clase_css' => 'w-5 h-5'],
            ['nombre' => 'building-office',          'variante' => 'outline', 'componente' => 'heroicon-o-building-office',          'clase_css' => 'w-5 h-5'],
            ['nombre' => 'briefcase',                'variante' => 'outline', 'componente' => 'heroicon-o-briefcase',                'clase_css' => 'w-5 h-5'],
            ['nombre' => 'user',                     'variante' => 'outline', 'componente' => 'heroicon-o-user',                     'clase_css' => 'w-5 h-5'],
            ['nombre' => 'tag',                      'variante' => 'outline', 'componente' => 'heroicon-o-tag',                      'clase_css' => 'w-5 h-5'],
            ['nombre' => 'share',                    'variante' => 'outline', 'componente' => 'heroicon-o-share',                    'clase_css' => 'w-5 h-5'],
            // ── Directorio ─────────────────────────────────────────
            ['nombre' => 'phone',                    'variante' => 'outline', 'componente' => 'heroicon-o-phone',                    'clase_css' => 'w-5 h-5'],
            ['nombre' => 'user-plus',                'variante' => 'outline', 'componente' => 'heroicon-o-user-plus',                'clase_css' => 'w-5 h-5'],
            ['nombre' => 'user-group',               'variante' => 'outline', 'componente' => 'heroicon-o-user-group',               'clase_css' => 'w-5 h-5'],
            ['nombre' => 'magnifying-glass',         'variante' => 'outline', 'componente' => 'heroicon-o-magnifying-glass',         'clase_css' => 'w-5 h-5'],
            // ── Reportes ───────────────────────────────────────────
            ['nombre' => 'document-text',            'variante' => 'outline', 'componente' => 'heroicon-o-document-text',            'clase_css' => 'w-5 h-5'],
            ['nombre' => 'printer',                  'variante' => 'outline', 'componente' => 'heroicon-o-printer',                  'clase_css' => 'w-5 h-5'],
            ['nombre' => 'arrow-down-tray',          'variante' => 'outline', 'componente' => 'heroicon-o-arrow-down-tray',          'clase_css' => 'w-5 h-5'],
            // ── Acciones CRUD ──────────────────────────────────────
            ['nombre' => 'pencil-square',            'variante' => 'outline', 'componente' => 'heroicon-o-pencil-square',            'clase_css' => 'w-4 h-4'],
            ['nombre' => 'trash',                    'variante' => 'outline', 'componente' => 'heroicon-o-trash',                    'clase_css' => 'w-4 h-4'],
            ['nombre' => 'eye',                      'variante' => 'outline', 'componente' => 'heroicon-o-eye',                      'clase_css' => 'w-4 h-4'],
            ['nombre' => 'plus-circle',              'variante' => 'outline', 'componente' => 'heroicon-o-plus-circle',              'clase_css' => 'w-4 h-4'],
            ['nombre' => 'lock-closed',              'variante' => 'outline', 'componente' => 'heroicon-o-lock-closed',              'clase_css' => 'w-5 h-5'],
            ['nombre' => 'key',                      'variante' => 'outline', 'componente' => 'heroicon-o-key',                      'clase_css' => 'w-5 h-5'],
        ];

        foreach ($iconos as $icono) {
            DB::table('iconos')->updateOrInsert(
                ['componente' => $icono['componente']],
                array_merge($icono, ['libreria' => 'heroicons', 'activo' => true])
            );
        }
    }
}