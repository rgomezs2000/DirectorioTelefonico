<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->smallIncrements('id_menu');
            $table->string('nombre', 80)->unique();
            $table->string('descripcion', 255)->nullable();
            $table->unsignedSmallInteger('id_icono')->nullable();
            $table->string('ruta', 200)->nullable()
                  ->comment('Named route o URL');
            $table->unsignedTinyInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->dateTime('creado_en')->useCurrent();
            $table->dateTime('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('id_icono', 'fk_menus_icono')
                  ->references('id_icono')->on('iconos')
                  ->onUpdate('cascade')->onDelete('set null');

            $table->index('id_icono', 'idx_menus_icono');
            $table->index('orden',    'idx_menus_orden');
            $table->index('activo',   'idx_menus_activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};