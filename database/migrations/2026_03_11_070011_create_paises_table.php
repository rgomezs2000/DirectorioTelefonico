<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paises', function (Blueprint $table) {
            $table->smallIncrements('id_pais');
            $table->string('nombre', 100)->notNull();
            $table->string('nombre_oficial', 150)->nullable();
            $table->char('iso2', 2)->unique();
            $table->char('iso3', 3)->unique();
            $table->char('codigo_numerico', 3)->nullable();
            $table->string('codigo_telefono', 10)->nullable();
            $table->string('continente', 30)->nullable();
            $table->string('capital', 100)->nullable();
            $table->char('moneda', 3)->nullable();
            $table->string('idioma_oficial', 80)->nullable();
            $table->boolean('activo')->default(true);
            $table->datetime('creado_en')->useCurrent();

            $table->index('continente');
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paises');
    }
};