<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int         $id_sesion
 * @property int         $id_usuario
 * @property string      $token_sesion
 * @property string|null $ip_origen
 * @property string|null $user_agent
 * @property string|null $dispositivo
 * @property bool        $activa
 * @property string|null $expira_en
 * @property string|null $cerrada_en
 * @property string      $creado_en
 */
class Sesion extends BaseModel
{
    protected $table      = 'sesiones';
    protected $primaryKey = 'id_sesion';

    /** sin actualizado_en en esta tabla */
    public $timestamps = false;
    const  CREATED_AT  = 'creado_en';
    const  UPDATED_AT  = null;

    protected $fillable = [
        'id_usuario',
        'token_sesion',
        'ip_origen',
        'user_agent',
        'dispositivo',
        'activa',
        'expira_en',
        'cerrada_en',
    ];

    protected $hidden = [
        'token_sesion',
    ];

    protected $casts = [
        'activa'     => 'boolean',
        'expira_en'  => 'datetime',
        'cerrada_en' => 'datetime',
    ];

    // ── Relaciones ────────────────────────────────────────────────

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // ── Scopes propios ────────────────────────────────────────────
    // Nota: scopeActivos usa la columna "activo" del BaseModel.
    // Esta tabla usa "activa" (femenino), por lo que definimos su propio scope.

    /** Solo sesiones activas y no expiradas */
    public function scopeVigentes(Builder $query): Builder
    {
        return $query->where('activa', true)
                     ->where(fn(Builder $q) =>
                         $q->whereNull('expira_en')
                           ->orWhere('expira_en', '>', now())
                     );
    }

    /** Solo sesiones ya cerradas */
    public function scopeCerradas(Builder $query): Builder
    {
        return $query->where('activa', false);
    }

    // ── Helpers ──────────────────────────────────────────────────

    /** Retorna true si la sesión está vigente */
    public function estaVigente(): bool
    {
        if (! $this->activa) {
            return false;
        }

        return is_null($this->expira_en) || $this->expira_en->isFuture();
    }

    /** Cierra la sesión actual */
    public function cerrar(): bool
    {
        return $this->update([
            'activa'     => false,
            'cerrada_en' => now(),
        ]);
    }

    /**
     * Registra una nueva sesión activa y devuelve su id_sesion.
     */
    public static function registrarSesion(int $idUsuario, string $login, object $sesion): ?int
    {
        $registro = self::create([
            'id_usuario'    => $idUsuario,
            'token_sesion'  => (string) ($sesion->token_sesion ?? ''),
            'ip_origen'     => (string) ($sesion->ip_origen ?? ''),
            'user_agent'    => $login,
            'dispositivo'   => (string) ($sesion->dispositivo ?? ''),
            'activa'        => true,
            'cerrada_en'    => null,
        ]);

        return $registro?->id_sesion;
    }

    /**
     * Cierra una sesión activa por id de usuario e id de sesión.
     */
    public static function cerrarSesion(int $idUsuario, int $idSesion): bool
    {
        return self::query()
            ->where('id_usuario', $idUsuario)
            ->where('id_sesion', $idSesion)
            ->where('activa', true)
            ->whereNull('cerrada_en')
            ->update([
                'activa'     => false,
                'cerrada_en' => now(),
            ]) > 0;
    }
}
