<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_token', function (Blueprint $table) {
            $table->id();
            $table->string('api_token');
            $table->index('api_token', 'idx_api_token_token');
            $table->dateTime('fecha_token_inicio');
            $table->dateTime('fecha_fin_token');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_token');
    }
};
