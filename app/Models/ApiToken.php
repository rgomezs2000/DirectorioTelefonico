<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * @property int    $id
 * @property string $api_token
 * @property string $fecha_token_inicio
 * @property string $fecha_fin_token
 * @property bool   $usado
 */
class ApiToken extends BaseModel
{
    protected $table      = 'api_token';
    protected $primaryKey = 'id';

    /**
     * La tabla api_token no usa creado_en/actualizado_en.
     */
    public $timestamps = false;
    const CREATED_AT   = null;
    const UPDATED_AT   = null;

    protected $fillable = [
        'api_token',
        'fecha_token_inicio',
        'fecha_fin_token',
        'usado',
    ];

    protected $hidden = [
        'api_token',
    ];

    protected $casts = [
        'fecha_token_inicio' => 'datetime',
        'fecha_fin_token'    => 'datetime',
        'usado'              => 'boolean',
    ];

    /**
     * Crea un token API con vigencia de 30 minutos y retorna el último token creado.
     */
    public static function obtenerToken(): object
    {
        $inicio = now();
        $fin = now()->addMinutes(30);

        $token = substr(hash('sha512', Str::uuid()->toString().microtime(true).Str::random(128)).hash('sha512', Str::random(128).microtime(true)), 0, 255);

        self::create([
            'api_token'         => $token,
            'fecha_token_inicio'=> $inicio,
            'fecha_fin_token'   => $fin,
            'usado'             => false,
        ]);

        $ultimoToken = self::query()->latest('id')->first();

        return (object) [
            'codigo'  => 200,
            'mensaje' => 'exitoso',
            'data'    => $ultimoToken,
        ];
    }

    // ── Scopes propios ────────────────────────────────────────────

    /** Tokens que ya están vigentes, no vencen y no fueron usados */
    public function scopeVigentes(Builder $query): Builder
    {
        return $query->where('fecha_token_inicio', '<=', now())
                     ->where('fecha_fin_token', '>', now())
                     ->where('usado', false);
    }
  
    /** Tokens expirados */
    public function scopeExpirados(Builder $query): Builder
    {
        return $query->where('fecha_fin_token', '<=', now());
    }

    /** Retorna true si el token está actualmente vigente y sin usar */
    public function estaVigente(): bool
    {
        return ! $this->usado
            && $this->fecha_token_inicio->lessThanOrEqualTo(now())
            && $this->fecha_fin_token->isFuture();
    }

    /** Marca como usado un token si existe y no está usado (false o null). */
    public static function tokenUsado(string $token): bool
    {
        return self::where('api_token', $token)
            ->where(function (Builder $query): void {
                $query->where('usado', false)
                    ->orWhereNull('usado');
            })
            ->update(['usado' => true]) > 0;
    }
}
