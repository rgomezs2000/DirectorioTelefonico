<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int         $id_log
 * @property int|null    $id_usuario
 * @property string      $tabla_afectada
 * @property int|null    $id_registro
 * @property string      $accion        INSERT|UPDATE|DELETE|LOGIN|LOGOUT|ACTIVATE|PASSWORD_CHANGE|PASSWORD_RESET
 * @property array|null  $datos_previos
 * @property array|null  $datos_nuevos
 * @property string|null $ip_origen
 * @property string|null $descripcion
 * @property string      $creado_en
 */
class AuditoriaLog extends BaseModel
{
    protected $table      = 'auditoria_log';
    protected $primaryKey = 'id_log';

    /** solo tiene creado_en, sin actualizado_en */
    public $timestamps = false;
    const  CREATED_AT  = 'creado_en';
    const  UPDATED_AT  = null;

    protected $fillable = [
        'id_usuario',
        'tabla_afectada',
        'id_registro',
        'accion',
        'datos_previos',
        'datos_nuevos',
        'ip_origen',
        'descripcion',
    ];

    protected $casts = [
        'datos_previos' => 'array',
        'datos_nuevos'  => 'array',
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

    /** Filtra por tabla afectada */
    public function scopeTabla(Builder $query, string $tabla): Builder
    {
        return $query->where('tabla_afectada', $tabla);
    }

    /** Filtra por tipo de acción */
    public function scopeAccion(Builder $query, string $accion): Builder
    {
        return $query->where('accion', strtoupper($accion));
    }

    /** Solo logs de acceso (LOGIN / LOGOUT) */
    public function scopeAccesos(Builder $query): Builder
    {
        return $query->whereIn('accion', ['LOGIN', 'LOGOUT']);
    }

    /** Solo logs de cambios de datos (INSERT / UPDATE / DELETE) */
    public function scopeCambios(Builder $query): Builder
    {
        return $query->whereIn('accion', ['INSERT', 'UPDATE', 'DELETE']);
    }

    // ── Helpers estáticos ─────────────────────────────────────────

    /**
     * Registra una entrada de auditoría.
     *
     * Uso:
     *   AuditoriaLog::registrar(
     *       tabla:       'usuarios',
     *       accion:      'UPDATE',
     *       idRegistro:  $usuario->id_usuario,
     *       idUsuario:   auth()->id(),
     *       previos:     $original,
     *       nuevos:      $cambios,
     *   );
     */
    public static function registrar(
        string $tabla,
        string $accion,
        ?int   $idRegistro = null,
        ?int   $idUsuario  = null,
        ?array $previos    = null,
        ?array $nuevos     = null,
        ?string $descripcion = null
    ): static {
        return static::create([
            'id_usuario'     => $idUsuario,
            'tabla_afectada' => $tabla,
            'id_registro'    => $idRegistro,
            'accion'         => strtoupper($accion),
            'datos_previos'  => $previos,
            'datos_nuevos'   => $nuevos,
            'ip_origen'      => request()?->ip(),
            'descripcion'    => $descripcion,
        ]);
    }
}