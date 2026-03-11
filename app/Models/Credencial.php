<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════════
//  CREDENCIAL  |  Tabla: credenciales
// ══════════════════════════════════════════════════════════════════
class Credencial extends BaseModel
{
    protected $table      = 'credenciales';
    protected $primaryKey = 'id_credencial';

    protected $hidden = [
        'password_hash',
        'token_activacion',
        'token_recuperacion',
        'token_refresh',
    ];

    protected $fillable = [
        'id_usuario', 'password_hash', 'algoritmo',
        'token_activacion', 'token_activacion_exp', 'token_activacion_usado',
        'token_recuperacion', 'token_recuperacion_exp', 'token_recuperacion_uso',
        'token_refresh', 'token_refresh_exp',
        'debe_cambiar_pass', 'intentos_fallidos', 'bloqueado_hasta',
        'ultimo_cambio_pass',
    ];

    protected $casts = [
        'token_activacion_exp'   => 'datetime',
        'token_recuperacion_exp' => 'datetime',
        'token_refresh_exp'      => 'datetime',
        'bloqueado_hasta'        => 'datetime',
        'ultimo_cambio_pass'     => 'datetime',
        'creado_en'              => 'datetime',
        'actualizado_en'         => 'datetime',
        'token_activacion_usado' => 'boolean',
        'token_recuperacion_uso' => 'boolean',
        'debe_cambiar_pass'      => 'boolean',
        'intentos_fallidos'      => 'integer',
    ];

    // ── Relaciones ────────────────────────────────────────────────
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // ── Helpers de tokens ─────────────────────────────────────────

    /** Genera y guarda un nuevo token de activación. Retorna el token en claro. */
    public function generarTokenActivacion(int $horasExpira = 24): string
    {
        $tokenClaro = Str::random(64);
        $this->update([
            'token_activacion'       => hash('sha256', $tokenClaro),
            'token_activacion_exp'   => now()->addHours($horasExpira),
            'token_activacion_usado' => false,
        ]);
        return $tokenClaro;
    }

    /** Genera y guarda un nuevo token de recuperación. Retorna el token en claro. */
    public function generarTokenRecuperacion(int $minutosExpira = 60): string
    {
        $tokenClaro = Str::random(64);
        $this->update([
            'token_recuperacion'     => hash('sha256', $tokenClaro),
            'token_recuperacion_exp' => now()->addMinutes($minutosExpira),
            'token_recuperacion_uso' => false,
        ]);
        return $tokenClaro;
    }

    /** Verifica si el token de activación en claro es válido. */
    public function tokenActivacionValido(string $tokenClaro): bool
    {
        return ! $this->token_activacion_usado
            && $this->token_activacion_exp?->isFuture()
            && hash_equals($this->token_activacion, hash('sha256', $tokenClaro));
    }

    /** Verifica si el token de recuperación en claro es válido. */
    public function tokenRecuperacionValido(string $tokenClaro): bool
    {
        return ! $this->token_recuperacion_uso
            && $this->token_recuperacion_exp?->isFuture()
            && hash_equals($this->token_recuperacion, hash('sha256', $tokenClaro));
    }

    /** Cambia la contraseña y limpia tokens de recuperación. */
    public function cambiarPassword(string $nuevaClave): void
    {
        $this->update([
            'password_hash'          => Hash::make($nuevaClave),
            'token_recuperacion_uso' => true,
            'ultimo_cambio_pass'     => now(),
            'intentos_fallidos'      => 0,
            'bloqueado_hasta'        => null,
            'debe_cambiar_pass'      => false,
        ]);
    }

    /** Registra un intento fallido y bloquea si supera el límite. */
    public function registrarIntentoFallido(int $maxIntentos = 5, int $minutosBloq = 30): void
    {
        $intentos = $this->intentos_fallidos + 1;
        $datos    = ['intentos_fallidos' => $intentos];

        if ($intentos >= $maxIntentos) {
            $datos['bloqueado_hasta'] = now()->addMinutes($minutosBloq);
        }

        $this->update($datos);
    }

    /** Retorna true si la cuenta está bloqueada por intentos fallidos. */
    public function estaBloqueada(): bool
    {
        return $this->bloqueado_hasta && $this->bloqueado_hasta->isFuture();
    }
}
