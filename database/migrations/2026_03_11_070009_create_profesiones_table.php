<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profesiones', function (Blueprint $table) {
            $table->increments('id_profesion');
            $table->string('nombre', 120)->unique();
            $table->string('descripcion', 400)->nullable();
            $table->string('categoria', 80)->nullable();
            $table->boolean('activo')->default(true);
            $table->datetime('creado_en')->useCurrent();

            $table->index('categoria');
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profesiones');
    }
};