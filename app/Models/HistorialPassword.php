<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int    $id_historial
 * @property int    $id_usuario
 * @property string $password_hash
 * @property string $algoritmo
 * @property string $creado_en
 */
class HistorialPassword extends BaseModel
{
    protected $table      = 'historial_passwords';
    protected $primaryKey = 'id_historial';

    /** sin actualizado_en en esta tabla */
    public $timestamps = false;
    const  CREATED_AT  = 'creado_en';
    const  UPDATED_AT  = null;

    protected $fillable = [
        'id_usuario',
        'password_hash',
        'algoritmo',
    ];

    protected $hidden = [
        'password_hash',
    ];

    // ── Relaciones ────────────────────────────────────────────────

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // ── Helpers estáticos ─────────────────────────────────────────

    /**
     * Comprueba si un hash ya fue utilizado por el usuario
     * en los últimos $ultimos registros.
     */
    public static function yaUsado(int $idUsuario, string $hashNuevo, int $ultimos = 5): bool
    {
        return static::where('id_usuario', $idUsuario)
            ->orderByDesc('creado_en')
            ->limit($ultimos)
            ->pluck('password_hash')
            ->contains(fn($hash) => \Illuminate\Support\Facades\Hash::check(
                // Nota: esto requiere tener el plain-text; en la práctica
                // compara hashes directamente según el algoritmo del proyecto.
                $hashNuevo, $hash
            ));
    }
}