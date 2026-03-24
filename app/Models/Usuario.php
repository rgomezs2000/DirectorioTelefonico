<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

    public function ultimaSesion(): HasOne
    {
        return $this->hasOne(Sesion::class, 'id_usuario', 'id_usuario')
            ->latestOfMany('creado_en');
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

    /**
     * Método de prueba para validar login + password.
     *
     * Retorna un arreglo con:
     * - codigo: 200/202/203
     * - mensaje: resultado legible
     * - data: datos del usuario y relaciones cuando es exitoso
     */
    public static function validarLoginTest(string $login, string $password): array
    {
        $usuario = self::query()
            ->where('username', $login)
            ->orWhere('email', $login)
            ->first();

        if (! $usuario) {
            return [
                'codigo'  => 202,
                'mensaje' => 'El usuario no existe',
                'data'    => null,
            ];
        }

        $credencial = Credencial::query()
            ->where('id_usuario', $usuario->id_usuario)
            ->first();

        if (! $credencial || ! Hash::check($password, $credencial->password_hash)) {
            return [
                'codigo'  => 203,
                'mensaje' => 'Clave inválida',
                'data'    => null,
            ];
        }

        $usuario->load(['tipoUsuario', 'credencial', 'sesiones', 'permisosIndividuales']);

        $permisosTipo = Permiso::query()
            ->where('id_tipo_usuario', $usuario->id_tipo_usuario)
            ->get();

        return [
            'codigo'  => 200,
            'mensaje' => 'Login válido',
            'data'    => [
                'usuario'          => $usuario,
                'credencial'       => $usuario->credencial,
                'sesiones'         => $usuario->sesiones,
                'tipo_usuario'     => $usuario->tipoUsuario,
                'permisos'         => $permisosTipo,
                'permisos_usuario' => $usuario->permisosIndividuales,
            ],
        ];
    }

    /**
     * Busca el usuario para autenticación por username o email.
     *
     * Retorna un objeto con los datos de:
     * - Usuario
     * - Credencial (incluyendo password_hash)
     * - TipoUsuario
     * - Sexo
     * - Permiso (por tipo de usuario)
     * - PermisoUsuario (permisos individuales)
     */
    public static function validarLogin(string $login, ?string $password = null): object
    {
        $usuario = self::query()
            ->where(static function (Builder $query) use ($login) {
                $query->where('username', $login)
                    ->orWhere('email', $login);
            })
            ->with([
                'tipoUsuario',
                'sexo',
                'credencial' => static function ($query) {
                    $query->select([
                        'id_credencial',
                        'id_usuario',
                        'password_hash',
                        'algoritmo',
                        'debe_cambiar_pass',
                        'intentos_fallidos',
                        'bloqueado_hasta',
                        'ultimo_cambio_pass',
                        'creado_en',
                        'actualizado_en',
                    ]);
                },
                'permisosIndividuales',
                'ultimaSesion',
            ])
            ->first();

        if (! $usuario) {
            return (object) [
                'codigo'  => 408,
                'mensaje' => 'login no existe',
                'data'    => null,
            ];
        }

        $resultadoBloqueo = self::usuarioBloqueado($usuario);

        if (($resultadoBloqueo->codigo ?? 200) !== 200) {
            return $resultadoBloqueo;
        }

        $permisosTipo = Permiso::query()
            ->where('id_tipo_usuario', $usuario->id_tipo_usuario)
            ->get();

        $usuario->setRelation('permisosTipo', $permisosTipo);

        if ($usuario->credencial) {
            $usuario->credencial->makeVisible(['password_hash']);
        }

        if ($password !== null) {
            $resultadoIntento = Credencial::bloqueoIntento((int) $usuario->id_usuario, $password);

            if ($resultadoIntento['fallido']) {
                return (object) [
                    'codigo'  => ($resultadoIntento['intentos'] ?? 0) >= 3 ? 309 : 308,
                    'mensaje' => $resultadoIntento['mensaje'] ?? 'Contraseña incorrecta',
                    'data'    => null,
                ];
            }

            Credencial::limpiarIntentos((int) $usuario->id_usuario);
        }

        $data = json_decode($usuario->toJson(), true);

        return (object) [
            'codigo'  => 200,
            'mensaje' => 'login encontrado',
            'data'    => $data,
        ];
    }

    public static function usuarioBloqueado(self $usuario): object
    {
        if ((bool) $usuario->bloqueado) {
            return (object) [
                'codigo'  => 407,
                'mensaje' => 'Su usuario se encuentra bloqueado, favor contactar con el administrador del sistema',
                'data'    => null,
            ];
        }

        return (object) [
            'codigo'  => 200,
            'mensaje' => 'usuario habilitado',
            'data'    => null,
        ];
    }


    /**
     * Busca el usuario para autenticación por email (Google).
     *
     * Retorna la misma estructura de validarLogin().
     */
    public static function validarLoginGoogle(string $email): object
    {
        $usuario = self::query()
            ->where('email', $email)
            ->with([
                'tipoUsuario',
                'sexo',
                'credencial' => static function ($query) {
                    $query->select([
                        'id_credencial',
                        'id_usuario',
                        'password_hash',
                        'algoritmo',
                        'debe_cambiar_pass',
                        'intentos_fallidos',
                        'bloqueado_hasta',
                        'ultimo_cambio_pass',
                        'creado_en',
                        'actualizado_en',
                    ]);
                },
                'permisosIndividuales',
                'ultimaSesion',
            ])
            ->first();

        if (! $usuario) {
            return (object) [
                'codigo'  => 408,
                'mensaje' => 'login no existe',
                'data'    => null,
            ];
        }

        $permisosTipo = Permiso::query()
            ->where('id_tipo_usuario', $usuario->id_tipo_usuario)
            ->get();

        $usuario->setRelation('permisosTipo', $permisosTipo);

        if ($usuario->credencial) {
            $usuario->credencial->makeVisible(['password_hash']);
        }

        $data = json_decode($usuario->toJson(), true);

        return (object) [
            'codigo'  => 200,
            'mensaje' => 'login encontrado',
            'data'    => $data,
        ];
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
