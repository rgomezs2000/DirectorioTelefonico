<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

/**
 * @property int         $id_credencial
 * @property int         $id_usuario
 * @property string      $password_hash
 * @property string      $algoritmo
 * @property string|null $token_activacion
 * @property string|null $token_activacion_exp
 * @property bool        $token_activacion_usado
 * @property string|null $token_recuperacion
 * @property string|null $token_recuperacion_exp
 * @property bool        $token_recuperacion_uso
 * @property string|null $token_refresh
 * @property string|null $token_refresh_exp
 * @property bool        $debe_cambiar_pass
 * @property int         $intentos_fallidos
 * @property string|null $bloqueado_hasta
 * @property string|null $ultimo_cambio_pass
 * @property string      $creado_en
 * @property string      $actualizado_en
 */
class Credencial extends BaseModel
{
    protected $table      = 'credenciales';
    protected $primaryKey = 'id_credencial';

    protected $fillable = [
        'id_usuario',
        'password_hash',
        'algoritmo',
        'token_activacion',
        'token_activacion_exp',
        'token_activacion_usado',
        'token_recuperacion',
        'token_recuperacion_exp',
        'token_recuperacion_uso',
        'token_refresh',
        'token_refresh_exp',
        'debe_cambiar_pass',
        'intentos_fallidos',
        'bloqueado_hasta',
        'ultimo_cambio_pass',
    ];

    protected $hidden = [
        'password_hash',
        'token_activacion',
        'token_recuperacion',
        'token_refresh',
    ];

    protected $casts = [
        'token_activacion_usado' => 'boolean',
        'token_recuperacion_uso' => 'boolean',
        'debe_cambiar_pass'      => 'boolean',
        'intentos_fallidos'      => 'integer',
        'token_activacion_exp'   => 'datetime',
        'token_recuperacion_exp' => 'datetime',
        'token_refresh_exp'      => 'datetime',
        'bloqueado_hasta'        => 'datetime',
        'ultimo_cambio_pass'     => 'datetime',
    ];

    // ── Relaciones ────────────────────────────────────────────────

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // ── Scopes propios ────────────────────────────────────────────

    /** Cuentas que están actualmente bloqueadas por intentos fallidos */
    public function scopeBloqueadas(Builder $query): Builder
    {
        return $query->whereNotNull('bloqueado_hasta')
                     ->where('bloqueado_hasta', '>', now());
    }

    /** Cuentas que deben cambiar contraseña en el próximo acceso */
    public function scopeDebenCambiarPass(Builder $query): Builder
    {
        return $query->where('debe_cambiar_pass', true);
    }

    // ── Helpers ──────────────────────────────────────────────────

    /** Retorna true si la cuenta está actualmente bloqueada */
    public function estaBloqueada(): bool
    {
        return $this->bloqueado_hasta && $this->bloqueado_hasta->isFuture();
    }

    /** Incrementa los intentos fallidos y bloquea si supera el límite */
    public function registrarIntentoFallido(int $maxIntentos = 5, int $minutosBloqueado = 30): void
    {
        $this->increment('intentos_fallidos');

        if ($this->intentos_fallidos >= $maxIntentos) {
            $this->update([
                'bloqueado_hasta' => now()->addMinutes($minutosBloqueado),
            ]);
        }
    }

    /** Limpia los intentos fallidos tras un login exitoso */
    public function limpiarIntentosFallidos(): void
    {
        $this->update([
            'intentos_fallidos' => 0,
            'bloqueado_hasta'   => null,
        ]);
    }

    /**
     * Valida el password y controla el bloqueo por intentos fallidos.
     *
     * @return array{intentos:int,fallido:bool,mensaje:string}
     */
    public static function bloqueoIntento(int $idUsuario, string $password): array
    {
        $credencial = self::query()->where('id_usuario', $idUsuario)->first();

        if (! $credencial || $password === '') {
            return [
                'intentos' => 0,
                'fallido'  => true,
                'mensaje'  => 'Contraseña incorrecta. Te queda 2 intentos',
            ];
        }

        $passwordCoincide = false;

        if (Hash::check($password, (string) $credencial->password_hash)) {
            $passwordCoincide = true;
        } else {
            try {
                $passwordDesencriptado = Crypt::decryptString((string) $credencial->password_hash);
                $passwordCoincide = hash_equals($passwordDesencriptado, $password);
            } catch (\Throwable) {
                $passwordCoincide = false;
            }
        }

        if ($passwordCoincide) {
            $credencial->update([
                'intentos_fallidos' => 0,
                'bloqueado_hasta'   => null,
            ]);

            Usuario::query()
                ->where('id_usuario', $idUsuario)
                ->update(['bloqueado' => false]);

            return [
                'intentos' => 0,
                'fallido'  => false,
                'mensaje'  => '',
            ];
        }

        $intentos = (int) $credencial->intentos_fallidos + 1;

        $credencial->update(['intentos_fallidos' => $intentos]);

        if ($intentos >= 3) {
            Usuario::query()
                ->where('id_usuario', $idUsuario)
                ->update(['bloqueado' => true]);

            return [
                'intentos' => 3,
                'fallido'  => true,
                'mensaje'  => 'Contraseña incorrecta. Su usuario ha sido bloqueado. Favor comunicarse con el Administrador del sistema',
            ];
        }

        $intentosRestantes = 3 - $intentos;

        return [
            'intentos' => $intentos,
            'fallido'  => true,
            'mensaje'  => "Contraseña incorrecta. Te queda {$intentosRestantes} intentos",
        ];
    }
}
