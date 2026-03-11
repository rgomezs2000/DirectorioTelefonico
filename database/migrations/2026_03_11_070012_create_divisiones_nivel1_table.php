<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('divisiones_nivel1', function (Blueprint $table) {
            $table->increments('id_nivel1');
            $table->unsignedSmallInteger('id_pais');
            $table->string('nombre', 120);
            $table->string('codigo', 10)->nullable();
            $table->string('tipo', 50)->nullable();
            $table->string('capital', 100)->nullable();
            $table->boolean('activo')->default(true);
            $table->datetime('creado_en')->useCurrent();

            $table->foreign('id_pais')
                  ->references('id_pais')->on('paises')
                  ->onUpdate('cascade')->onDelete('restrict');

            $table->index('id_pais');
            $table->index('nombre');
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('divisiones_nivel1');
    }
};