<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Credenciales ─────────────────────────────────────────────
        Schema::create('credenciales', function (Blueprint $table) {
            $table->bigIncrements('id_credencial');
            $table->unsignedBigInteger('id_usuario')->unique();
            $table->string('password_hash', 255);
            $table->string('algoritmo', 30)->default('bcrypt');
            // Token de activación
            $table->string('token_activacion', 255)->nullable();
            $table->datetime('token_activacion_exp')->nullable();
            $table->boolean('token_activacion_usado')->default(false);
            // Token de recuperación
            $table->string('token_recuperacion', 255)->nullable();
            $table->datetime('token_recuperacion_exp')->nullable();
            $table->boolean('token_recuperacion_uso')->default(false);
            // Token refresh / sesión
            $table->string('token_refresh', 512)->nullable();
            $table->datetime('token_refresh_exp')->nullable();
            // Estado
            $table->boolean('debe_cambiar_pass')->default(false);
            $table->unsignedTinyInteger('intentos_fallidos')->default(0);
            $table->datetime('bloqueado_hasta')->nullable();
            // Auditoría
            $table->datetime('ultimo_cambio_pass')->nullable();
            $table->datetime('creado_en')->useCurrent();
            $table->datetime('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('id_usuario')
                  ->references('id_usuario')->on('usuarios')
                  ->onUpdate('cascade')->onDelete('cascade');

            $table->index($table->getTable() . '_token_act' , 'idx_cred_token_act');
            $table->index('bloqueado_hasta');
        });

        // ── Historial de contraseñas ──────────────────────────────────
        Schema::create('historial_passwords', function (Blueprint $table) {
            $table->bigIncrements('id_historial');
            $table->unsignedBigInteger('id_usuario');
            $table->string('password_hash', 255);
            $table->string('algoritmo', 30)->default('bcrypt');
            $table->datetime('creado_en')->useCurrent();

            $table->foreign('id_usuario')
                  ->references('id_usuario')->on('usuarios')
                  ->onUpdate('cascade')->onDelete('cascade');

            $table->index('id_usuario');
            $table->index('creado_en');
        });

        // ── Sesiones ──────────────────────────────────────────────────
        Schema::create('sesiones', function (Blueprint $table) {
            $table->bigIncrements('id_sesion');
            $table->unsignedBigInteger('id_usuario');
            $table->string('token_sesion', 512);
            $table->string('ip_origen', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('dispositivo', 100)->nullable();
            $table->boolean('activa')->default(true);
            $table->datetime('expira_en')->nullable();
            $table->datetime('cerrada_en')->nullable();
            $table->datetime('creado_en')->useCurrent();

            $table->foreign('id_usuario')
                  ->references('id_usuario')->on('usuarios')
                  ->onUpdate('cascade')->onDelete('cascade');

            $table->index('id_usuario');
            $table->index('activa');
            $table->index('expira_en');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesiones');
        Schema::dropIfExists('historial_passwords');
        Schema::dropIfExists('credenciales');
    }
};