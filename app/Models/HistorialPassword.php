<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════════
//  HISTORIAL PASSWORD  |  Tabla: historial_passwords
// ══════════════════════════════════════════════════════════════════
class HistorialPassword extends BaseModel
{
    protected $table      = 'historial_passwords';
    protected $primaryKey = 'id_historial';
    const UPDATED_AT      = null;

    protected $hidden   = ['password_hash'];
    protected $fillable = ['id_usuario', 'password_hash', 'algoritmo'];

    protected $casts = ['creado_en' => 'datetime'];

    // ── Relaciones ────────────────────────────────────────────────
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // ── Helpers ───────────────────────────────────────────────────

    /** Verifica si la clave ya fue usada en el historial del usuario. */
    public static function yaFueUsada(int $idUsuario, string $nuevaClave, int $ultimos = 5): bool
    {
        return static::query()
            ->where('id_usuario', $idUsuario)
            ->latest('creado_en')
            ->limit($ultimos)
            ->get()
            ->contains(fn($h) => Hash::check($nuevaClave, $h->password_hash));
    }
}
