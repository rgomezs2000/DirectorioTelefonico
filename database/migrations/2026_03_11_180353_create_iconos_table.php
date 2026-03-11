<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iconos', function (Blueprint $table) {
            $table->smallIncrements('id_icono');
            $table->string('nombre', 80)
                  ->comment('Clave SVG de Heroicons (ej: home, users)');
            $table->string('libreria', 40)->default('heroicons');
            $table->enum('variante', ['outline', 'solid', 'mini'])->default('outline');
            $table->string('componente', 120)->unique()
                  ->comment('Componente Blade completo: heroicon-o-home');
            $table->string('clase_css', 200)->default('w-5 h-5')
                  ->comment('Clases Tailwind listas para HTML');
            $table->boolean('activo')->default(true);

            $table->index('libreria', 'idx_iconos_libreria');
            $table->index('variante', 'idx_iconos_variante');
            $table->index('activo',   'idx_iconos_activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iconos');
    }
};