<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * ╔══════════════════════════════════════════════════════════════╗
 *  BASE MODEL — Directorio Telefónico
 *  Todos los modelos del proyecto extienden esta clase.
 *
 *  Centraliza:
 *   · Timestamps personalizados  (creado_en / actualizado_en)
 *   · Scopes reutilizables       (activos, inactivos, buscar, recientes)
 *   · Helpers de instancia       (activar, desactivar, toggleActivo)
 *   · Formato de fechas ISO 8601
 * ╚══════════════════════════════════════════════════════════════╝
 */
abstract class BaseModel extends Model
{
    // ── Timestamps personalizados ─────────────────────────────────
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $dateFormat = 'Y-m-d H:i:s';
    protected $connection = 'mysql';

    protected $casts = [
        'creado_en'      => 'datetime',
        'actualizado_en' => 'datetime',
    ];

    // ── Scopes globales reutilizables ─────────────────────────────

    /** Solo registros activos. Uso: Modelo::activos()->get() */
    public function scopeActivos(Builder $query): Builder
    {
        return $query->where($this->getTable() . '.activo', true);
    }

    /** Solo registros inactivos. Uso: Modelo::inactivos()->get() */
    public function scopeInactivos(Builder $query): Builder
    {
        return $query->where($this->getTable() . '.activo', false);
    }

    /**
     * Búsqueda LIKE en múltiples columnas.
     * Uso: Modelo::buscar('Juan', ['nombres','apellidos'])->get()
     */
    public function scopeBuscar(Builder $query, string $termino, array $columnas): Builder
    {
        return $query->where(function (Builder $q) use ($termino, $columnas) {
            foreach ($columnas as $columna) {
                $q->orWhere($columna, 'like', "%{$termino}%");
            }
        });
    }

    /** Ordenar por fecha de creación DESC. */
    public function scopeRecientes(Builder $query): Builder
    {
        return $query->orderByDesc('creado_en');
    }

    /** Ordenar por fecha de creación ASC. */
    public function scopeAntiguos(Builder $query): Builder
    {
        return $query->orderBy('creado_en');
    }

    // ── Helpers de instancia ──────────────────────────────────────

    /** Activa el registro. */
    public function activar(): bool
    {
        return $this->update(['activo' => true]);
    }

    /** Desactiva el registro. */
    public function desactivar(): bool
    {
        return $this->update(['activo' => false]);
    }

    /** Alterna activo ↔ inactivo. */
    public function toggleActivo(): bool
    {
        return $this->update(['activo' => ! $this->activo]);
    }

    /** Retorna true si el registro está activo. */
    public function estaActivo(): bool
    {
        return (bool) $this->activo;
    }

    /** Convierte a array eliminando valores nulos. */
    public function toArraySinNulos(): array
    {
        return array_filter($this->toArray(), fn($v) => ! is_null($v));
    }
}