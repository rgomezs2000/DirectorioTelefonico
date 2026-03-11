<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Teléfonos de contacto ─────────────────────────────────────
        Schema::create('telefonos_contacto', function (Blueprint $table) {
            $table->bigIncrements('id_telefono');
            $table->unsignedBigInteger('id_contacto');
            $table->unsignedTinyInteger('id_tipo_telefono');
            $table->unsignedSmallInteger('id_pais')->nullable();
            $table->string('numero', 30);
            $table->string('extension', 10)->nullable();
            $table->boolean('es_principal')->default(false);
            $table->boolean('activo')->default(true);
            $table->datetime('creado_en')->useCurrent();

            $table->foreign('id_contacto')
                  ->references('id_contacto')->on('contactos')
                  ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_tipo_telefono')
                  ->references('id_tipo_telefono')->on('tipos_telefono')
                  ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_pais')
                  ->references('id_pais')->on('paises')
                  ->onUpdate('cascade')->onDelete('set null');

            $table->index('id_contacto');
            $table->index('numero');
            $table->index('id_tipo_telefono');
        });

        // ── Emails de contacto ────────────────────────────────────────
        Schema::create('emails_contacto', function (Blueprint $table) {
            $table->bigIncrements('id_email');
            $table->unsignedBigInteger('id_contacto');
            $table->unsignedTinyInteger('id_tipo_email');
            $table->string('email', 180);
            $table->boolean('es_principal')->default(false);
            $table->boolean('activo')->default(true);
            $table->datetime('creado_en')->useCurrent();

            $table->foreign('id_contacto')
                  ->references('id_contacto')->on('contactos')
                  ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_tipo_email')
                  ->references('id_tipo_email')->on('tipos_email')
                  ->onUpdate('cascade')->onDelete('restrict');

            $table->index('id_contacto');
            $table->index('email');
        });

        // ── Direcciones de contacto ───────────────────────────────────
        Schema::create('direcciones_contacto', function (Blueprint $table) {
            $table->bigIncrements('id_direccion');
            $table->unsignedBigInteger('id_contacto');
            $table->unsignedTinyInteger('id_tipo_direccion');
            $table->unsignedSmallInteger('id_pais')->nullable();
            $table->unsignedInteger('id_nivel1')->nullable();
            $table->unsignedInteger('id_nivel2')->nullable();
            $table->unsignedInteger('id_nivel3')->nullable();
            $table->string('direccion_linea1', 200);
            $table->string('direccion_linea2', 200)->nullable();
            $table->string('codigo_postal', 15)->nullable();
            $table->string('referencia', 300)->nullable();
            $table->boolean('es_principal')->default(false);
            $table->boolean('activo')->default(true);
            $table->datetime('creado_en')->useCurrent();

            $table->foreign('id_contacto')
                  ->references('id_contacto')->on('contactos')
                  ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_tipo_direccion')
                  ->references('id_tipo_direccion')->on('tipos_direccion')
                  ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_pais')
                  ->references('id_pais')->on('paises')
                  ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('id_nivel1')
                  ->references('id_nivel1')->on('divisiones_nivel1')
                  ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('id_nivel2')
                  ->references('id_nivel2')->on('divisiones_nivel2')
                  ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('id_nivel3')
                  ->references('id_nivel3')->on('divisiones_nivel3')
                  ->onUpdate('cascade')->onDelete('set null');

            $table->index('id_contacto');
            $table->index('id_nivel2');
            $table->index('codigo_postal');
        });

        // ── Redes sociales de contacto ────────────────────────────────
        Schema::create('redes_contacto', function (Blueprint $table) {
            $table->bigIncrements('id_red_contacto');
            $table->unsignedBigInteger('id_contacto');
            $table->unsignedTinyInteger('id_red_social');
            $table->string('usuario_red', 150);
            $table->string('url_perfil', 400)->nullable();
            $table->boolean('es_principal')->default(false);
            $table->boolean('activo')->default(true);
            $table->datetime('creado_en')->useCurrent();

            $table->foreign('id_contacto')
                  ->references('id_contacto')->on('contactos')
                  ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_red_social')
                  ->references('id_red_social')->on('redes_sociales')
                  ->onUpdate('cascade')->onDelete('restrict');

            $table->index('id_contacto');
            $table->index('id_red_social');
        });

        // ── Grupos de contacto ────────────────────────────────────────
        Schema::create('grupos_contacto', function (Blueprint $table) {
            $table->bigIncrements('id_grupo');
            $table->unsignedBigInteger('id_usuario');
            $table->string('nombre', 80);
            $table->string('descripcion', 300)->nullable();
            $table->char('color_hex', 7)->nullable();
            $table->boolean('activo')->default(true);
            $table->datetime('creado_en')->useCurrent();

            $table->foreign('id_usuario')
                  ->references('id_usuario')->on('usuarios')
                  ->onUpdate('cascade')->onDelete('cascade');

            $table->index('id_usuario');
        });

        // ── Tabla pivote contacto ↔ grupo ─────────────────────────────
        Schema::create('contacto_grupo', function (Blueprint $table) {
            $table->unsignedBigInteger('id_contacto');
            $table->unsignedBigInteger('id_grupo');
            $table->datetime('agregado_en')->useCurrent();

            $table->primary(['id_contacto', 'id_grupo']);

            $table->foreign('id_contacto')
                  ->references('id_contacto')->on('contactos')
                  ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_grupo')
                  ->references('id_grupo')->on('grupos_contacto')
                  ->onUpdate('cascade')->onDelete('cascade');

            $table->index('id_grupo');
            $table->index('id_contacto');
        });

        // ── Auditoría ─────────────────────────────────────────────────
        Schema::create('auditoria_log', function (Blueprint $table) {
            $table->bigIncrements('id_log');
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->string('tabla_afectada', 80);
            $table->unsignedBigInteger('id_registro')->nullable();
            $table->enum('accion', [
                'INSERT','UPDATE','DELETE','LOGIN','LOGOUT',
                'ACTIVATE','PASSWORD_CHANGE','PASSWORD_RESET',
            ]);
            $table->json('datos_previos')->nullable();
            $table->json('datos_nuevos')->nullable();
            $table->string('ip_origen', 45)->nullable();
            $table->string('descripcion', 500)->nullable();
            $table->datetime('creado_en')->useCurrent();

            $table->foreign('id_usuario')
                  ->references('id_usuario')->on('usuarios')
                  ->onUpdate('cascade')->onDelete('set null');

            $table->index('id_usuario');
            $table->index('tabla_afectada');
            $table->index('accion');
            $table->index('creado_en');
        });

        // ── Configuración de usuario ──────────────────────────────────
        Schema::create('configuracion_usuario', function (Blueprint $table) {
            $table->bigIncrements('id_config');
            $table->unsignedBigInteger('id_usuario');
            $table->string('clave', 80);
            $table->string('valor', 500)->nullable();
            $table->string('descripcion', 200)->nullable();
            $table->datetime('creado_en')->useCurrent();
            $table->datetime('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['id_usuario', 'clave']);

            $table->foreign('id_usuario')
                  ->references('id_usuario')->on('usuarios')
                  ->onUpdate('cascade')->onDelete('cascade');

            $table->index('id_usuario');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion_usuario');
        Schema::dropIfExists('auditoria_log');
        Schema::dropIfExists('contacto_grupo');
        Schema::dropIfExists('grupos_contacto');
        Schema::dropIfExists('redes_contacto');
        Schema::dropIfExists('direcciones_contacto');
        Schema::dropIfExists('emails_contacto');
        Schema::dropIfExists('telefonos_contacto');
    }
};