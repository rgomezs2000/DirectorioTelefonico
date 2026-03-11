<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// ══════════════════════════════════════════════════════════════════
//  USUARIO  |  Tabla: usuarios
//  Extiende Authenticatable (no BaseModel) para soporte Auth de Laravel
// ══════════════════════════════════════════════════════════════════
class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table      = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public    $timestamps = true;
    const CREATED_AT      = 'creado_en';
    const UPDATED_AT      = 'actualizado_en';

    protected $fillable = [
        'id_tipo_usuario', 'id_sexo', 'id_profesion',
        'id_nivel3', 'id_nivel2', 'id_nivel1', 'id_pais',
        'username', 'email',
        'nombres', 'apellidos', 'fecha_nacimiento',
        'foto_perfil_url', 'bio',
        'activo', 'email_verificado', 'bloqueado', 'ultimo_acceso',
    ];

    protected $hidden = ['credencial'];

    protected $casts = [
        'fecha_nacimiento'  => 'date',
        'ultimo_acceso'     => 'datetime',
        'creado_en'         => 'datetime',
        'actualizado_en'    => 'datetime',
        'activo'            => 'boolean',
        'email_verificado'  => 'boolean',
        'bloqueado'         => 'boolean',
    ];

    // ── Auth helpers ──────────────────────────────────────────────
    public function getAuthPassword(): string
    {
        return $this->credencial?->password_hash ?? '';
    }

    public function getEmailForPasswordReset(): string
    {
        return $this->email;
    }

    // ── Relaciones: catálogos ─────────────────────────────────────
    public function tipoUsuario()
    {
        return $this->belongsTo(TipoUsuario::class, 'id_tipo_usuario', 'id_tipo_usuario');
    }

    public function sexo()
    {
        return $this->belongsTo(Sexo::class, 'id_sexo', 'id_sexo');
    }

    public function profesion()
    {
        return $this->belongsTo(Profesion::class, 'id_profesion', 'id_profesion');
    }

    // ── Relaciones: geografía ─────────────────────────────────────
    public function pais()
    {
        return $this->belongsTo(Pais::class, 'id_pais', 'id_pais');
    }

    public function nivel1()
    {
        return $this->belongsTo(DivisionNivel1::class, 'id_nivel1', 'id_nivel1');
    }

    public function nivel2()
    {
        return $this->belongsTo(DivisionNivel2::class, 'id_nivel2', 'id_nivel2');
    }

    public function nivel3()
    {
        return $this->belongsTo(DivisionNivel3::class, 'id_nivel3', 'id_nivel3');
    }

    // ── Relaciones: seguridad ─────────────────────────────────────
    public function credencial()
    {
        return $this->hasOne(Credencial::class, 'id_usuario', 'id_usuario');
    }

    public function historialPasswords()
    {
        return $this->hasMany(HistorialPassword::class, 'id_usuario', 'id_usuario');
    }

    public function sesiones()
    {
        return $this->hasMany(Sesion::class, 'id_usuario', 'id_usuario');
    }

    // ── Relaciones: directorio ────────────────────────────────────
    public function contactos()
    {
        return $this->hasMany(Contacto::class, 'id_usuario', 'id_usuario');
    }

    public function grupos()
    {
        return $this->hasMany(GrupoContacto::class, 'id_usuario', 'id_usuario');
    }

    public function configuraciones()
    {
        return $this->hasMany(ConfiguracionUsuario::class, 'id_usuario', 'id_usuario');
    }

    // ── Scopes ────────────────────────────────────────────────────
    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('usuarios.activo', true);
    }

    public function scopeVerificados(Builder $query): Builder
    {
        return $query->where('email_verificado', true);
    }

    public function scopeBloqueados(Builder $query): Builder
    {
        return $query->where('bloqueado', true);
    }

    public function scopePorTipo(Builder $query, int $idTipo): Builder
    {
        return $query->where('id_tipo_usuario', $idTipo);
    }

    public function scopeBuscar(Builder $query, string $termino): Builder
    {
        return $query->where(function (Builder $q) use ($termino) {
            $q->where('nombres',   'like', "%{$termino}%")
              ->orWhere('apellidos','like', "%{$termino}%")
              ->orWhere('username', 'like', "%{$termino}%")
              ->orWhere('email',    'like', "%{$termino}%");
        });
    }

    // ── Helpers ───────────────────────────────────────────────────
    public function nombreCompleto(): string
    {
        return trim("{$this->nombres} {$this->apellidos}");
    }

    public function esSuperAdmin(): bool
    {
        return $this->tipoUsuario?->nivel_acceso >= 100;
    }

    public function esAdmin(): bool
    {
        return $this->tipoUsuario?->nivel_acceso >= 80;
    }

    public function registrarAcceso(): void
    {
        $this->update(['ultimo_acceso' => now()]);
    }
}
