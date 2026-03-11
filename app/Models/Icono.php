<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int    $id_icono
 * @property string $nombre      Clave SVG de Heroicons (ej: home)
 * @property string $libreria    Siempre "heroicons"
 * @property string $variante    outline | solid | mini
 * @property string $componente  Componente Blade: heroicon-o-home
 * @property string $clase_css   Clases Tailwind: w-5 h-5
 * @property bool   $activo
 */
class Icono extends BaseModel
{
    protected $table      = 'iconos';
    protected $primaryKey = 'id_icono';

    /**
     * La tabla iconos no tiene columnas creado_en / actualizado_en,
     * así que desactivamos los timestamps del BaseModel para esta clase.
     */
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'libreria',
        'variante',
        'componente',
        'clase_css',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // ── Relaciones ────────────────────────────────────────────────

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class, 'id_icono', 'id_icono');
    }

    public function submenus(): HasMany
    {
        return $this->hasMany(Submenu::class, 'id_icono', 'id_icono');
    }

    public function modulos(): HasMany
    {
        return $this->hasMany(Modulo::class, 'id_icono', 'id_icono');
    }

    // ── Scopes propios ────────────────────────────────────────────

    /** Filtra por variante: outline | solid | mini */
    public function scopeVariante(Builder $query, string $variante): Builder
    {
        return $query->where('variante', $variante);
    }

    // ── Helpers ──────────────────────────────────────────────────

    /**
     * Devuelve el tag Blade listo para insertar en una vista.
     * Ejemplo:  <x-heroicon-o-home class="w-5 h-5" />
     */
    public function tagBlade(?string $extraClases = null): string
    {
        $clases = $extraClases ?? $this->clase_css;
        return "<x-{$this->componente} class=\"{$clases}\" />";
    }

    /**
     * Prefijo de variante para construir el componente dinámicamente.
     * Retorna: 'o' | 's' | 'm'
     */
    public function prefijo(): string
    {
        return match ($this->variante) {
            'solid' => 's',
            'mini'  => 'm',
            default => 'o',
        };
    }
}