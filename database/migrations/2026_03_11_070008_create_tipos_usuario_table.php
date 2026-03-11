<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_usuario', function (Blueprint $table) {
            $table->tinyIncrements('id_tipo_usuario');
            $table->string('nombre', 60)->unique();
            $table->string('descripcion', 300)->nullable();
            $table->unsignedTinyInteger('nivel_acceso')->default(1);
            $table->boolean('activo')->default(true);
            $table->datetime('creado_en')->useCurrent();

            $table->index('nivel_acceso');
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_usuario');
    }
};