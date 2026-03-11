<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('divisiones_nivel3', function (Blueprint $table) {
            $table->increments('id_nivel3');
            $table->unsignedInteger('id_nivel2');
            $table->string('nombre', 120);
            $table->string('codigo', 20)->nullable();
            $table->string('tipo', 50)->nullable();
            $table->string('codigo_postal', 15)->nullable();
            $table->boolean('activo')->default(true);
            $table->datetime('creado_en')->useCurrent();

            $table->foreign('id_nivel2')
                  ->references('id_nivel2')->on('divisiones_nivel2')
                  ->onUpdate('cascade')->onDelete('restrict');

            $table->index('id_nivel2');
            $table->index('nombre');
            $table->index('codigo_postal');
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('divisiones_nivel3');
    }
};