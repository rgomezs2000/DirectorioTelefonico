<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->bigIncrements('id_usuario');
            $table->unsignedTinyInteger('id_tipo_usuario');
            $table->unsignedTinyInteger('id_sexo')->nullable();
            $table->unsignedInteger('id_profesion')->nullable();
            // Ubicación
            $table->unsignedInteger('id_nivel3')->nullable();
            $table->unsignedInteger('id_nivel2')->nullable();
            $table->unsignedInteger('id_nivel1')->nullable();
            $table->unsignedSmallInteger('id_pais')->nullable();
            // Acceso
            $table->string('username', 60)->unique();
            $table->string('email', 180)->unique();
            // Datos personales
            $table->string('nombres', 100);
            $table->string('apellidos', 100)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('foto_perfil_url', 500)->nullable();
            $table->text('bio')->nullable();
            // Estado
            $table->boolean('activo')->default(true);
            $table->boolean('email_verificado')->default(false);
            $table->boolean('bloqueado')->default(false);
            // Auditoría
            $table->datetime('ultimo_acceso')->nullable();
            $table->datetime('creado_en')->useCurrent();
            $table->datetime('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            // Claves foráneas
            $table->foreign('id_tipo_usuario')
                  ->references('id_tipo_usuario')->on('tipos_usuario')
                  ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_sexo')
                  ->references('id_sexo')->on('sexos')
                  ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('id_profesion')
                  ->references('id_profesion')->on('profesiones')
                  ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('id_nivel3')
                  ->references('id_nivel3')->on('divisiones_nivel3')
                  ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('id_nivel2')
                  ->references('id_nivel2')->on('divisiones_nivel2')
                  ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('id_nivel1')
                  ->references('id_nivel1')->on('divisiones_nivel1')
                  ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('id_pais')
                  ->references('id_pais')->on('paises')
                  ->onUpdate('cascade')->onDelete('set null');

            // Índices
            $table->index('id_tipo_usuario');
            $table->index('id_sexo');
            $table->index('id_profesion');
            $table->index('id_pais');
            $table->index('id_nivel1');
            $table->index('id_nivel2');
            $table->index('id_nivel3');
            $table->index('activo');
            $table->index('email_verificado');
            $table->index(['nombres', 'apellidos']);
            $table->index('ultimo_acceso');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};