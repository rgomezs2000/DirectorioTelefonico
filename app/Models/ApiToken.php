<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property int    $id
 * @property string $api_token
 * @property string $fecha_token_inicio
 * @property string $fecha_fin_token
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
    ];

    protected $hidden = [
        'api_token',
    ];

    protected $casts = [
        'fecha_token_inicio' => 'datetime',
        'fecha_fin_token'    => 'datetime',
    ];

    // ── Scopes propios ────────────────────────────────────────────

    /** Tokens que ya están vigentes y aún no vencen */
    public function scopeVigentes(Builder $query): Builder
    {
        return $query->where('fecha_token_inicio', '<=', now())
                     ->where('fecha_fin_token', '>', now());
    }

    /** Tokens expirados */
    public function scopeExpirados(Builder $query): Builder
    {
        return $query->where('fecha_fin_token', '<=', now());
    }

    // ── Helpers ──────────────────────────────────────────────────

    /** Retorna true si el token está actualmente vigente */
    public function estaVigente(): bool
    {
        return $this->fecha_token_inicio->lessThanOrEqualTo(now())
            && $this->fecha_fin_token->isFuture();
    }
}
