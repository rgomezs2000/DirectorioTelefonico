<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int         $id_usuario
 * @property int         $id_tipo_usuario
 * @property int|null    $id_sexo
 * @property int|null    $id_profesion
 * @property int|null    $id_nivel3
 * @property int|null    $id_nivel2
 * @property int|null    $id_nivel1
 * @property int|null    $id_pais
 * @property string      $username
 * @property string      $email
 * @property string      $nombres
 * @property string|null $apellidos
 * @property string|null $fecha_nacimiento
 * @property string|null $foto_perfil_url
 * @property string|null $bio
 * @property bool        $activo
 * @property bool        $email_verificado
 * @property bool        $bloqueado
 * @property string|null $ultimo_acceso
 * @property string      $creado_en
 * @property string      $actualizado_en
 */
class Usuario extends BaseModel implements AuthenticatableContract
{
    use Authenticatable;

    protected $table      = 'usuarios';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'id_tipo_usuario',
        'id_sexo',
        'id_profesion',
        'id_nivel3',
        'id_nivel2',
        'id_nivel1',
        'id_pais',
        'username',
        'email',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'foto_perfil_url',
        'bio',
        'activo',
        'email_verificado',
        'bloqueado',
        'ultimo_acceso',
    ];

    protected $hidden = [
        'password', // requerido por el trait Authenticatable
    ];

    protected $casts = [
        'activo'          => 'boolean',
        'email_verificado'=> 'boolean',
        'bloqueado'       => 'boolean',
        'fecha_nacimiento'=> 'date',
        'ultimo_acceso'   => 'datetime',
    ];

    // ── Relaciones ────────────────────────────────────────────────

    public function tipoUsuario(): BelongsTo
    {
        return $this->belongsTo(TipoUsuario::class, 'id_tipo_usuario', 'id_tipo_usuario');
    }

    public function sexo(): BelongsTo
    {
        return $this->belongsTo(Sexo::class, 'id_sexo', 'id_sexo');
    }

    public function profesion(): BelongsTo
    {
        return $this->belongsTo(Profesion::class, 'id_profesion', 'id_profesion');
    }

    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class, 'id_pais', 'id_pais');
    }

    public function nivel1(): BelongsTo
    {
        return $this->belongsTo(DivisionNivel1::class, 'id_nivel1', 'id_nivel1');
    }

    public function nivel2(): BelongsTo
    {
        return $this->belongsTo(DivisionNivel2::class, 'id_nivel2', 'id_nivel2');
    }

    public function nivel3(): BelongsTo
    {
        return $this->belongsTo(DivisionNivel3::class, 'id_nivel3', 'id_nivel3');
    }

    public function credencial(): HasOne
    {
        return $this->hasOne(Credencial::class, 'id_usuario', 'id_usuario');
    }

    public function historialPasswords(): HasMany
    {
        return $this->hasMany(HistorialPassword::class, 'id_usuario', 'id_usuario')
                    ->orderByDesc('creado_en');
    }

    public function sesiones(): HasMany
    {
        return $this->hasMany(Sesion::class, 'id_usuario', 'id_usuario');
    }

    public function contactos(): HasMany
    {
        return $this->hasMany(Contacto::class, 'id_usuario', 'id_usuario');
    }

    public function gruposContacto(): HasMany
    {
        return $this->hasMany(GrupoContacto::class, 'id_usuario', 'id_usuario');
    }

    public function configuracion(): HasMany
    {
        return $this->hasMany(ConfiguracionUsuario::class, 'id_usuario', 'id_usuario');
    }

    public function permisosIndividuales(): HasMany
    {
        return $this->hasMany(PermisoUsuario::class, 'id_usuario', 'id_usuario');
    }

    public function auditoriaLog(): HasMany
    {
        return $this->hasMany(AuditoriaLog::class, 'id_usuario', 'id_usuario');
    }

    // ── Scopes propios ────────────────────────────────────────────

    /** Solo usuarios no bloqueados */
    public function scopeNoBloqueados(Builder $query): Builder
    {
        return $query->where('bloqueado', false);
    }

    /** Solo usuarios con email verificado */
    public function scopeVerificados(Builder $query): Builder
    {
        return $query->where('email_verificado', true);
    }

    /** Filtra por tipo de usuario */
    public function scopeTipo(Builder $query, int $idTipoUsuario): Builder
    {
        return $query->where('id_tipo_usuario', $idTipoUsuario);
    }

    // ── Helpers ──────────────────────────────────────────────────

    /** Nombre completo: nombres + apellidos */
    public function nombreCompleto(): string
    {
        return trim("{$this->nombres} {$this->apellidos}");
    }

    /** Verifica si el usuario está activo y no bloqueado */
    public function puedeAcceder(): bool
    {
        return $this->activo && ! $this->bloqueado;
    }

    /**
     * Requerido por el contrato Authenticatable: clave primaria del auth.
     * Apunta a la columna id_usuario en lugar del default "id".
     */
    public function getAuthIdentifierName(): string
    {
        return 'id_usuario';
    }

    public function getAuthIdentifier(): mixed
    {
        return $this->id_usuario;
    }

    /**
     * El password lo almacena la tabla credenciales, no usuarios.
     * Para compatibilidad con los guards de Laravel devolvemos
     * el hash desde la relación credencial si existe.
     */
    public function getAuthPassword(): string
    {
        return $this->credencial?->password_hash ?? '';
    }
}