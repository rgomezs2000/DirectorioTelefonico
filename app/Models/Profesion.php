<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════════
//  PROFESION  |  Tabla: profesiones
// ══════════════════════════════════════════════════════════════════
class Profesion extends BaseModel
{
    protected $table      = 'profesiones';
    protected $primaryKey = 'id_profesion';
    const UPDATED_AT      = null;

    protected $fillable = ['nombre', 'descripcion', 'categoria', 'activo'];

    protected $casts = [
        'activo'    => 'boolean',
        'creado_en' => 'datetime',
    ];

    // ── Relaciones ────────────────────────────────────────────────
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_profesion', 'id_profesion');
    }

    public function contactos()
    {
        return $this->hasMany(Contacto::class, 'id_profesion', 'id_profesion');
    }

    // ── Scopes ────────────────────────────────────────────────────
    public function scopePorCategoria(mixed $query, string $categoria): mixed
    {
        return $query->where('categoria', $categoria);
    }

    public static function categorias(): array
    {
        return static::query()
            ->whereNotNull('categoria')
            ->distinct()
            ->orderBy('categoria')
            ->pluck('categoria')
            ->toArray();
    }
}
