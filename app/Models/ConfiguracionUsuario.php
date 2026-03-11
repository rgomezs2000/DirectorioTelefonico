<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════════
//  CONFIGURACION USUARIO  |  Tabla: configuracion_usuario
// ══════════════════════════════════════════════════════════════════
class ConfiguracionUsuario extends BaseModel
{
    protected $table      = 'configuracion_usuario';
    protected $primaryKey = 'id_config';

    protected $fillable = ['id_usuario', 'clave', 'valor', 'descripcion'];

    protected $casts = [
        'creado_en'      => 'datetime',
        'actualizado_en' => 'datetime',
    ];

    // ── Relaciones ────────────────────────────────────────────────
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // ── Helpers estáticos ─────────────────────────────────────────

    /** Obtiene el valor de una configuración para un usuario. */
    public static function obtener(int $idUsuario, string $clave, mixed $default = null): mixed
    {
        $config = static::query()
            ->where('id_usuario', $idUsuario)
            ->where('clave', $clave)
            ->first();

        return $config?->valor ?? $default;
    }

    /** Establece o actualiza una configuración para un usuario. */
    public static function establecer(int $idUsuario, string $clave, mixed $valor): static
    {
        return static::updateOrCreate(
            ['id_usuario' => $idUsuario, 'clave' => $clave],
            ['valor'      => $valor]
        );
    }
}
