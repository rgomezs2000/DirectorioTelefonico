<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int         $id_contacto
 * @property int         $id_usuario
 * @property int|null    $id_sexo
 * @property int|null    $id_profesion
 * @property int|null    $id_categoria
 * @property int|null    $id_pais
 * @property int|null    $id_nivel1
 * @property int|null    $id_nivel2
 * @property int|null    $id_nivel3
 * @property string      $nombres
 * @property string|null $apellidos
 * @property string|null $empresa
 * @property string|null $cargo
 * @property string|null $fecha_nacimiento
 * @property string|null $sitio_web
 * @property string|null $notas
 * @property string|null $foto_url
 * @property bool        $favorito
 * @property bool        $activo
 * @property string      $creado_en
 * @property string      $actualizado_en
 */
class Contacto extends BaseModel
{
    protected $table      = 'contactos';
    protected $primaryKey = 'id_contacto';

    protected $fillable = [
        'id_usuario',
        'id_sexo',
        'id_profesion',
        'id_categoria',
        'id_pais',
        'id_nivel1',
        'id_nivel2',
        'id_nivel3',
        'nombres',
        'apellidos',
        'empresa',
        'cargo',
        'fecha_nacimiento',
        'sitio_web',
        'notas',
        'foto_url',
        'favorito',
        'activo',
    ];

    protected $casts = [
        'favorito'        => 'boolean',
        'activo'          => 'boolean',
        'fecha_nacimiento'=> 'date',
    ];

    // ── Relaciones ────────────────────────────────────────────────

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function sexo(): BelongsTo
    {
        return $this->belongsTo(Sexo::class, 'id_sexo', 'id_sexo');
    }

    public function profesion(): BelongsTo
    {
        return $this->belongsTo(Profesion::class, 'id_profesion', 'id_profesion');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriaContacto::class, 'id_categoria', 'id_categoria');
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

    public function telefonos(): HasMany
    {
        return $this->hasMany(TelefonoContacto::class, 'id_contacto', 'id_contacto');
    }

    public function emails(): HasMany
    {
        return $this->hasMany(EmailContacto::class, 'id_contacto', 'id_contacto');
    }

    public function direcciones(): HasMany
    {
        return $this->hasMany(DireccionContacto::class, 'id_contacto', 'id_contacto');
    }

    public function redes(): HasMany
    {
        return $this->hasMany(RedContacto::class, 'id_contacto', 'id_contacto');
    }

    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(
            GrupoContacto::class,
            'contacto_grupo',
            'id_contacto',
            'id_grupo'
        )->withPivot('agregado_en');
    }

    // ── Scopes propios ────────────────────────────────────────────

    /** Solo contactos favoritos */
    public function scopeFavoritos(Builder $query): Builder
    {
        return $query->where('favorito', true);
    }

    /** Filtra por propietario */
    public function scopeDeUsuario(Builder $query, int $idUsuario): Builder
    {
        return $query->where('id_usuario', $idUsuario);
    }

    /** Filtra por categoría */
    public function scopeCategoria(Builder $query, int $idCategoria): Builder
    {
        return $query->where('id_categoria', $idCategoria);
    }

    /** Búsqueda por nombre, empresa o cargo */
    public function scopeBuscarContacto(Builder $query, string $termino): Builder
    {
        return $query->where(fn(Builder $q) =>
            $q->where('nombres', 'like', "%{$termino}%")
              ->orWhere('apellidos', 'like', "%{$termino}%")
              ->orWhere('empresa', 'like', "%{$termino}%")
              ->orWhere('cargo', 'like', "%{$termino}%")
        );
    }

    // ── Helpers ──────────────────────────────────────────────────

    /** Nombre completo del contacto */
    public function nombreCompleto(): string
    {
        return trim("{$this->nombres} {$this->apellidos}");
    }

    /** Teléfono principal si existe */
    public function telefonoPrincipal(): ?TelefonoContacto
    {
        return $this->telefonos()->where('es_principal', true)->first();
    }

    /** Email principal si existe */
    public function emailPrincipal(): ?EmailContacto
    {
        return $this->emails()->where('es_principal', true)->first();
    }
}