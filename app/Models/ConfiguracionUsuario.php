<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int         $id_config
 * @property int         $id_usuario
 * @property string      $clave
 * @property string|null $valor
 * @property string|null $descripcion
 * @property string      $creado_en
 * @property string      $actualizado_en
 */
class ConfiguracionUsuario extends BaseModel
{
    protected $table      = 'configuracion_usuario';
    protected $primaryKey = 'id_config';

    protected $fillable = [
        'id_usuario',
        'clave',
        'valor',
        'descripcion',
    ];

    // ── Relaciones ────────────────────────────────────────────────

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // ── Scopes propios ────────────────────────────────────────────

    /** Filtra por usuario */
    public function scopeDeUsuario(Builder $query, int $idUsuario): Builder
    {
        return $query->where('id_usuario', $idUsuario);
    }

    /** Filtra por clave de configuración */
    public function scopeClave(Builder $query, string $clave): Builder
    {
        return $query->where('clave', $clave);
    }

    // ── Helpers estáticos ─────────────────────────────────────────

    /**
     * Obtiene el valor de una clave de configuración para un usuario.
     * Devuelve $default si la clave no existe.
     *
     * Uso:
     *   ConfiguracionUsuario::obtener($idUsuario, 'tema', 'claro')
     */
    public static function obtener(int $idUsuario, string $clave, mixed $default = null): mixed
    {
        $config = static::where('id_usuario', $idUsuario)
            ->where('clave', $clave)
            ->value('valor');

        return $config ?? $default;
    }

    /**
     * Guarda o actualiza un valor de configuración para un usuario.
     *
     * Uso:
     *   ConfiguracionUsuario::guardar($idUsuario, 'tema', 'oscuro')
     */
    public static function guardar(int $idUsuario, string $clave, mixed $valor): static
    {
        return static::updateOrCreate(
            ['id_usuario' => $idUsuario, 'clave' => $clave],
            ['valor'      => (string) $valor]
        );
    }
}