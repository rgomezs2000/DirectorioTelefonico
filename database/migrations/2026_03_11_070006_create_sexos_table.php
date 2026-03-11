<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sexos', function (Blueprint $table) {
            $table->tinyIncrements('id_sexo');
            $table->string('nombre', 50)->unique();
            $table->char('abreviatura', 3)->unique();
            $table->string('descripcion', 200)->nullable();
            $table->boolean('activo')->default(true);
            $table->datetime('creado_en')->useCurrent();

            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sexos');
    }
};