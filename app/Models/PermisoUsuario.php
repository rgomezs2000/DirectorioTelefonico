<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermisoUsuario extends BaseModel
{
    protected $table = 'permisos_usuario';
    protected $primaryKey = 'id_permiso_usuario';

    protected $fillable = [
        'id_usuario',
        'id_menu',
        'id_submenu',
        'id_modulo',
        'puede_ver',
        'puede_crear',
        'puede_editar',
        'puede_eliminar',
        'puede_exportar',
        'puede_imprimir',
        'concedido',
        'motivo',
    ];

    protected $casts = [
        'puede_ver'      => 'boolean',
        'puede_crear'    => 'boolean',
        'puede_editar'   => 'boolean',
        'puede_eliminar' => 'boolean',
        'puede_exportar' => 'boolean',
        'puede_imprimir' => 'boolean',
        'concedido'      => 'boolean',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
