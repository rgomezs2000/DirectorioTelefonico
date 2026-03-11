<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 6.5  PERMISOS (tipo_usuario × menú / submenú / módulo) ──
        Schema::create('permisos', function (Blueprint $table) {
            $table->increments('id_permiso');
            $table->unsignedTinyInteger('id_tipo_usuario');
            $table->unsignedSmallInteger('id_menu')->nullable();
            $table->unsignedSmallInteger('id_submenu')->nullable();
            $table->unsignedSmallInteger('id_modulo')->nullable();

            $table->boolean('puede_ver')->default(false);
            $table->boolean('puede_crear')->default(false);
            $table->boolean('puede_editar')->default(false);
            $table->boolean('puede_eliminar')->default(false);
            $table->boolean('puede_exportar')->default(false);
            $table->boolean('puede_imprimir')->default(false);

            $table->dateTime('creado_en')->useCurrent();
            $table->dateTime('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            // Evitar duplicados por recurso
            $table->unique(
                ['id_tipo_usuario', 'id_menu', 'id_submenu', 'id_modulo'],
                'uq_perm_recurso'
            );

            $table->foreign('id_tipo_usuario', 'fk_perm_tipo')
                  ->references('id_tipo_usuario')->on('tipos_usuario')
                  ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('id_menu', 'fk_perm_menu')
                  ->references('id_menu')->on('menus')
                  ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('id_submenu', 'fk_perm_submenu')
                  ->references('id_submenu')->on('submenus')
                  ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('id_modulo', 'fk_perm_modulo')
                  ->references('id_modulo')->on('modulos')
                  ->onUpdate('cascade')->onDelete('cascade');

            $table->index('id_tipo_usuario', 'idx_perm_tipo');
            $table->index('id_menu',         'idx_perm_menu');
            $table->index('id_submenu',      'idx_perm_submenu');
            $table->index('id_modulo',       'idx_perm_modulo');
            $table->index('puede_ver',       'idx_perm_ver');
        });

        // ── 6.6  PERMISOS_USUARIO (sobreescritura individual) ────────
        Schema::create('permisos_usuario', function (Blueprint $table) {
            $table->increments('id_permiso_usuario');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedSmallInteger('id_menu')->nullable();
            $table->unsignedSmallInteger('id_submenu')->nullable();
            $table->unsignedSmallInteger('id_modulo')->nullable();

            $table->boolean('puede_ver')->default(false);
            $table->boolean('puede_crear')->default(false);
            $table->boolean('puede_editar')->default(false);
            $table->boolean('puede_eliminar')->default(false);
            $table->boolean('puede_exportar')->default(false);
            $table->boolean('puede_imprimir')->default(false);

            $table->boolean('concedido')->default(true)
                  ->comment('1=conceder acceso extra | 0=revocar acceso');
            $table->string('motivo', 300)->nullable();

            $table->dateTime('creado_en')->useCurrent();
            $table->dateTime('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            // Evitar duplicados por usuario + recurso
            $table->unique(
                ['id_usuario', 'id_menu', 'id_submenu', 'id_modulo'],
                'uq_pu_recurso'
            );

            $table->foreign('id_usuario', 'fk_pu_usuario')
                  ->references('id_usuario')->on('usuarios')
                  ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('id_menu', 'fk_pu_menu')
                  ->references('id_menu')->on('menus')
                  ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('id_submenu', 'fk_pu_submenu')
                  ->references('id_submenu')->on('submenus')
                  ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('id_modulo', 'fk_pu_modulo')
                  ->references('id_modulo')->on('modulos')
                  ->onUpdate('cascade')->onDelete('cascade');

            $table->index('id_usuario',  'idx_pu_usuario');
            $table->index('id_menu',     'idx_pu_menu');
            $table->index('id_submenu',  'idx_pu_submenu');
            $table->index('id_modulo',   'idx_pu_modulo');
            $table->index('concedido',   'idx_pu_concedido');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permisos_usuario');
        Schema::dropIfExists('permisos');
    }
};