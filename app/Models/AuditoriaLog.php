<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════════
//  AUDITORIA LOG  |  Tabla: auditoria_log
// ══════════════════════════════════════════════════════════════════
class AuditoriaLog extends BaseModel
{
    protected $table      = 'auditoria_log';
    protected $primaryKey = 'id_log';
    const UPDATED_AT      = null;

    protected $fillable = [
        'id_usuario', 'tabla_afectada', 'id_registro',
        'accion', 'datos_previos', 'datos_nuevos',
        'ip_origen', 'descripcion',
    ];

    protected $casts = [
        'datos_previos' => 'array',
        'datos_nuevos'  => 'array',
        'creado_en'     => 'datetime',
    ];

    // ── Relaciones ────────────────────────────────────────────────
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // ── Scopes ────────────────────────────────────────────────────
    public function scopePorAccion(mixed $query, string $accion): mixed
    {
        return $query->where('accion', strtoupper($accion));
    }

    public function scopePorTabla(mixed $query, string $tabla): mixed
    {
        return $query->where('tabla_afectada', $tabla);
    }

    public function scopePorUsuario(mixed $query, int $idUsuario): mixed
    {
        return $query->where('id_usuario', $idUsuario);
    }

    public function scopeHoy(mixed $query): mixed
    {
        return $query->whereDate('creado_en', today());
    }

    // ── Helpers estáticos ─────────────────────────────────────────

    /** Registra una entrada de auditoría de forma centralizada. */
    public static function registrar(
        string  $accion,
        string  $tabla,
        ?int    $idRegistro   = null,
        ?array  $datosPrevios = null,
        ?array  $datosNuevos  = null,
        ?int    $idUsuario    = null,
        ?string $ip           = null,
        ?string $descripcion  = null,
    ): static {
        return static::create([
            'id_usuario'      => $idUsuario ?? auth()->id(),
            'tabla_afectada'  => $tabla,
            'id_registro'     => $idRegistro,
            'accion'          => strtoupper($accion),
            'datos_previos'   => $datosPrevios,
            'datos_nuevos'    => $datosNuevos,
            'ip_origen'       => $ip ?? request()?->ip(),
            'descripcion'     => $descripcion,
        ]);
    }
}
