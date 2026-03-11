<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Tipos de teléfono ─────────────────────────────────────────
        Schema::create('tipos_telefono', function (Blueprint $table) {
            $table->tinyIncrements('id_tipo_telefono');
            $table->string('nombre', 50)->unique();
            $table->string('descripcion', 200)->nullable();
        });

        // ── Tipos de dirección ────────────────────────────────────────
        Schema::create('tipos_direccion', function (Blueprint $table) {
            $table->tinyIncrements('id_tipo_direccion');
            $table->string('nombre', 50)->unique();
            $table->string('descripcion', 200)->nullable();
        });

        // ── Tipos de email ────────────────────────────────────────────
        Schema::create('tipos_email', function (Blueprint $table) {
            $table->tinyIncrements('id_tipo_email');
            $table->string('nombre', 50)->unique();
        });

        // ── Categorías de contacto ────────────────────────────────────
        Schema::create('categorias_contacto', function (Blueprint $table) {
            $table->smallIncrements('id_categoria');
            $table->string('nombre', 80)->unique();
            $table->string('descripcion', 300)->nullable();
            $table->char('color_hex', 7)->nullable();
            $table->string('icono', 80)->nullable();
            $table->boolean('activo')->default(true);
        });

        // ── Redes sociales ────────────────────────────────────────────
        Schema::create('redes_sociales', function (Blueprint $table) {
            $table->tinyIncrements('id_red_social');
            $table->string('nombre', 50)->unique();
            $table->string('url_base', 200)->nullable();
            $table->string('icono', 80)->nullable();
        });

        // ── Contactos (tabla central) ─────────────────────────────────
        Schema::create('contactos', function (Blueprint $table) {
            $table->bigIncrements('id_contacto');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedTinyInteger('id_sexo')->nullable();
            $table->unsignedInteger('id_profesion')->nullable();
            $table->unsignedSmallInteger('id_categoria')->nullable();
            // Geografía
            $table->unsignedSmallInteger('id_pais')->nullable();
            $table->unsignedInteger('id_nivel1')->nullable();
            $table->unsignedInteger('id_nivel2')->nullable();
            $table->unsignedInteger('id_nivel3')->nullable();
            // Datos
            $table->string('nombres', 100);
            $table->string('apellidos', 100)->nullable();
            $table->string('empresa', 150)->nullable();
            $table->string('cargo', 100)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('sitio_web', 300)->nullable();
            $table->text('notas')->nullable();
            $table->string('foto_url', 500)->nullable();
            $table->boolean('favorito')->default(false);
            $table->boolean('activo')->default(true);
            $table->datetime('creado_en')->useCurrent();
            $table->datetime('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            // Claves foráneas
            $table->foreign('id_usuario')
                  ->references('id_usuario')->on('usuarios')
                  ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_sexo')
                  ->references('id_sexo')->on('sexos')
                  ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('id_profesion')
                  ->references('id_profesion')->on('profesiones')
                  ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('id_categoria')
                  ->references('id_categoria')->on('categorias_contacto')
                  ->onUpdate('cascade')->onDelete('set null');
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

            // Índices
            $table->index('id_usuario');
            $table->index(['nombres', 'apellidos']);
            $table->index('empresa');
            $table->index('id_categoria');
            $table->index('id_pais');
            $table->index('id_nivel2');
            $table->index('favorito');
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contactos');
        Schema::dropIfExists('redes_sociales');
        Schema::dropIfExists('categorias_contacto');
        Schema::dropIfExists('tipos_email');
        Schema::dropIfExists('tipos_direccion');
        Schema::dropIfExists('tipos_telefono');
    }
};