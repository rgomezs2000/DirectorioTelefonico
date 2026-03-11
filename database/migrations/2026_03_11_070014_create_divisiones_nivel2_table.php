<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('divisiones_nivel2', function (Blueprint $table) {
            $table->increments('id_nivel2');
            $table->unsignedInteger('id_nivel1');
            $table->string('nombre', 120);
            $table->string('codigo', 15)->nullable();
            $table->string('tipo', 50)->nullable();
            $table->string('capital', 100)->nullable();
            $table->unsignedInteger('poblacion')->nullable();
            $table->boolean('activo')->default(true);
            $table->datetime('creado_en')->useCurrent();

            $table->foreign('id_nivel1')
                  ->references('id_nivel1')->on('divisiones_nivel1')
                  ->onUpdate('cascade')->onDelete('restrict');

            $table->index('id_nivel1');
            $table->index('nombre');
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('divisiones_nivel2');
    }
};