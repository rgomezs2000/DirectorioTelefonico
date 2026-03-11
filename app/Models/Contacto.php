<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ══════════════════════════════════════════════════════════════════
//  CONTACTO  |  Tabla: contactos  (tabla central del directorio)
// ══════════════════════════════════════════════════════════════════
class Contacto extends BaseModel
{
    protected $table      = 'contactos';
    protected $primaryKey = 'id_contacto';

    protected $fillable = [
        'id_usuario', 'id_sexo', 'id_profesion', 'id_categoria',
        'id_pais', 'id_nivel1', 'id_nivel2', 'id_nivel3',
        'nombres', 'apellidos', 'empresa', 'cargo',
        'fecha_nacimiento', 'sitio_web', 'notas', 'foto_url',
        'favorito', 'activo',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'favorito'         => 'boolean',
        'activo'           => 'boolean',
        'creado_en'        => 'datetime',
        'actualizado_en'   => 'datetime',
    ];

    // ── Relaciones: propietario ───────────────────────────────────
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // ── Relaciones: catálogos ─────────────────────────────────────
    public function sexo()
    {
        return $this->belongsTo(Sexo::class, 'id_sexo', 'id_sexo');
    }

    public function profesion()
    {
        return $this->belongsTo(Profesion::class, 'id_profesion', 'id_profesion');
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriaContacto::class, 'id_categoria', 'id_categoria');
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

    // ── Relaciones: datos de contacto ─────────────────────────────
    public function telefonos()
    {
        return $this->hasMany(TelefonoContacto::class, 'id_contacto', 'id_contacto');
    }

    public function telefonoPrincipal()
    {
        return $this->hasOne(TelefonoContacto::class, 'id_contacto', 'id_contacto')
                    ->where('es_principal', true);
    }

    public function emails()
    {
        return $this->hasMany(EmailContacto::class, 'id_contacto', 'id_contacto');
    }

    public function emailPrincipal()
    {
        return $this->hasOne(EmailContacto::class, 'id_contacto', 'id_contacto')
                    ->where('es_principal', true);
    }

    public function direcciones()
    {
        return $this->hasMany(DireccionContacto::class, 'id_contacto', 'id_contacto');
    }

    public function redes()
    {
        return $this->hasMany(RedContacto::class, 'id_contacto', 'id_contacto');
    }

    public function grupos()
    {
        return $this->belongsToMany(
            GrupoContacto::class,
            'contacto_grupo',
            'id_contacto',
            'id_grupo'
        )->withPivot('agregado_en');
    }

    // ── Scopes ────────────────────────────────────────────────────
    public function scopeFavoritos(mixed $query): mixed
    {
        return $query->where('favorito', true);
    }

    public function scopeDeUsuario(mixed $query, int $idUsuario): mixed
    {
        return $query->where('id_usuario', $idUsuario);
    }

    public function scopeBuscar(mixed $query, string $termino): mixed
    {
        return $query->where(function ($q) use ($termino) {
            $q->where('nombres',   'like', "%{$termino}%")
              ->orWhere('apellidos','like', "%{$termino}%")
              ->orWhere('empresa',  'like', "%{$termino}%")
              ->orWhere('cargo',    'like', "%{$termino}%")
              ->orWhereHas('telefonos', fn($t) =>
                  $t->where('numero', 'like', "%{$termino}%")
              )
              ->orWhereHas('emails', fn($e) =>
                  $e->where('email', 'like', "%{$termino}%")
              );
        });
    }

    // ── Helpers ───────────────────────────────────────────────────
    public function nombreCompleto(): string
    {
        return trim("{$this->nombres} {$this->apellidos}");
    }

    public function marcarFavorito(bool $valor = true): bool
    {
        return $this->update(['favorito' => $valor]);
    }
}
