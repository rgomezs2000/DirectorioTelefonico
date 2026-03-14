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
    public static function validarLogin(string $login): object
    {
        $registro = DB::table('usuarios as u')
            ->leftJoin('credenciales as c', 'c.id_usuario', '=', 'u.id_usuario')
            ->leftJoin('tipos_usuario as tu', 'tu.id_tipo_usuario', '=', 'u.id_tipo_usuario')
            ->leftJoin('sexos as sx', 'sx.id_sexo', '=', 'u.id_sexo')
            ->where(static function ($query) use ($login) {
                $query->where('u.username', $login)
                    ->orWhere('u.email', $login);
            })
            ->select([
                'u.id_usuario',
                'u.id_tipo_usuario',
                'u.id_sexo',
                'u.id_profesion',
                'u.id_nivel3',
                'u.id_nivel2',
                'u.id_nivel1',
                'u.id_pais',
                'u.username',
                'u.email',
                'u.nombres',
                'u.apellidos',
                'u.fecha_nacimiento',
                'u.foto_perfil_url',
                'u.bio',
                'u.activo',
                'u.email_verificado',
                'u.bloqueado',
                'u.ultimo_acceso',
                'u.creado_en as usuario_creado_en',
                'u.actualizado_en as usuario_actualizado_en',
                'c.id_credencial',
                'c.password_hash',
                'c.algoritmo',
                'c.debe_cambiar_pass',
                'c.intentos_fallidos',
                'c.bloqueado_hasta',
                'c.ultimo_cambio_pass',
                'c.creado_en as credencial_creado_en',
                'c.actualizado_en as credencial_actualizado_en',
                'tu.nombre as tipo_usuario_nombre',
                'tu.descripcion as tipo_usuario_descripcion',
                'tu.nivel_acceso as tipo_usuario_nivel_acceso',
                'tu.activo as tipo_usuario_activo',
                'sx.nombre as sexo_nombre',
                'sx.abreviatura as sexo_abreviatura',
                'sx.descripcion as sexo_descripcion',
                'sx.activo as sexo_activo',
            ])
            ->selectRaw(
                '(SELECT JSON_OBJECT(' .
                    '"id_sesion", s.id_sesion,' .
                    '"id_usuario", s.id_usuario,' .
                    '"ip_origen", s.ip_origen,' .
                    '"user_agent", s.user_agent,' .
                    '"dispositivo", s.dispositivo,' .
                    '"activa", s.activa,' .
                    '"expira_en", s.expira_en,' .
                    '"cerrada_en", s.cerrada_en,' .
                    '"creado_en", s.creado_en' .
                ') FROM sesiones s ' .
                'WHERE s.id_usuario = u.id_usuario ' .
                'ORDER BY s.creado_en DESC, s.id_sesion DESC ' .
                'LIMIT 1) as sesion'
            )
            ->selectRaw(
                '(SELECT COALESCE(JSON_ARRAYAGG(JSON_OBJECT(' .
                    '"id_permiso", p.id_permiso,' .
                    '"id_tipo_usuario", p.id_tipo_usuario,' .
                    '"id_menu", p.id_menu,' .
                    '"id_submenu", p.id_submenu,' .
                    '"id_modulo", p.id_modulo,' .
                    '"puede_ver", p.puede_ver,' .
                    '"puede_crear", p.puede_crear,' .
                    '"puede_editar", p.puede_editar,' .
                    '"puede_eliminar", p.puede_eliminar,' .
                    '"puede_exportar", p.puede_exportar,' .
                    '"puede_imprimir", p.puede_imprimir' .
                ')), JSON_ARRAY()) ' .
                'FROM permisos p ' .
                'WHERE p.id_tipo_usuario = u.id_tipo_usuario) as permisos'
            )
            ->selectRaw(
                '(SELECT COALESCE(JSON_ARRAYAGG(JSON_OBJECT(' .
                    '"id_permiso_usuario", pu.id_permiso_usuario,' .
                    '"id_usuario", pu.id_usuario,' .
                    '"id_menu", pu.id_menu,' .
                    '"id_submenu", pu.id_submenu,' .
                    '"id_modulo", pu.id_modulo,' .
                    '"puede_ver", pu.puede_ver,' .
                    '"puede_crear", pu.puede_crear,' .
                    '"puede_editar", pu.puede_editar,' .
                    '"puede_eliminar", pu.puede_eliminar,' .
                    '"puede_exportar", pu.puede_exportar,' .
                    '"puede_imprimir", pu.puede_imprimir,' .
                    '"concedido", pu.concedido,' .
                    '"motivo", pu.motivo' .
                ')), JSON_ARRAY()) ' .
                'FROM permisos_usuario pu ' .
                'WHERE pu.id_usuario = u.id_usuario) as permisos_usuario'
            )
            ->first();

        if (! $registro) {
            return (object) [
                'codigo'  => 408,
                'mensaje' => 'login no existe',
                'data'    => null,
            ];
        }

        $registro->sesion = $registro->sesion ? json_decode($registro->sesion) : null;
        $registro->permisos = $registro->permisos ? json_decode($registro->permisos) : [];
        $registro->permisos_usuario = $registro->permisos_usuario ? json_decode($registro->permisos_usuario) : [];

        return (object) [
            'codigo'  => 200,
            'mensaje' => 'login encontrado',
            'data'    => $registro,
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
