<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════════
//  PAIS  |  Tabla: paises
// ══════════════════════════════════════════════════════════════════
class Pais extends BaseModel
{
    protected $table      = 'paises';
    protected $primaryKey = 'id_pais';
    const UPDATED_AT      = null;

    protected $fillable = [
        'nombre', 'nombre_oficial', 'iso2', 'iso3', 'codigo_numerico',
        'codigo_telefono', 'continente', 'capital', 'moneda',
        'idioma_oficial', 'activo',
    ];

    protected $casts = [
        'activo'    => 'boolean',
        'creado_en' => 'datetime',
    ];

    // ── Relaciones ────────────────────────────────────────────────
    public function divisiones1()
    {
        return $this->hasMany(DivisionNivel1::class, 'id_pais', 'id_pais');
    }

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_pais', 'id_pais');
    }

    public function contactos()
    {
        return $this->hasMany(Contacto::class, 'id_pais', 'id_pais');
    }

    public function telefonos()
    {
        return $this->hasMany(TelefonoContacto::class, 'id_pais', 'id_pais');
    }

    public function direcciones()
    {
        return $this->hasMany(DireccionContacto::class, 'id_pais', 'id_pais');
    }

    // ── Scopes ────────────────────────────────────────────────────
    public function scopePorContinente(mixed $query, string $continente): mixed
    {
        return $query->where('continente', $continente);
    }

    public function scopePorIso2(mixed $query, string $iso2): mixed
    {
        return $query->where('iso2', strtoupper($iso2));
    }

    // ── Helpers ───────────────────────────────────────────────────
    public static function buscarPorIso(string $iso): ?static
    {
        return static::query()
            ->where('iso2', strtoupper($iso))
            ->orWhere('iso3', strtoupper($iso))
            ->first();
    }

    public static function continentes(): array
    {
        return static::query()
            ->whereNotNull('continente')
            ->distinct()
            ->orderBy('continente')
            ->pluck('continente')
            ->toArray();
    }
}
