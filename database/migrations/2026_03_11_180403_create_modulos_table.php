<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submenus', function (Blueprint $table) {
            $table->smallIncrements('id_submenu');
            $table->unsignedSmallInteger('id_menu');
            $table->string('nombre', 80);
            $table->string('descripcion', 255)->nullable();
            $table->unsignedSmallInteger('id_icono')->nullable();
            $table->string('ruta', 200)->nullable();
            $table->unsignedTinyInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->dateTime('creado_en')->useCurrent();
            $table->dateTime('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('id_menu', 'fk_submenus_menu')
                  ->references('id_menu')->on('menus')
                  ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('id_icono', 'fk_submenus_icono')
                  ->references('id_icono')->on('iconos')
                  ->onUpdate('cascade')->onDelete('set null');

            $table->index('id_menu',  'idx_submenus_menu');
            $table->index('id_icono', 'idx_submenus_icono');
            $table->index('orden',    'idx_submenus_orden');
            $table->index('activo',   'idx_submenus_activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submenus');
    }
};