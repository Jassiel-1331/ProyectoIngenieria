<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cache_tmdb', function (Blueprint $table) {
            $table->id('id_tmdb');
            $table->enum('tipo', ['pelicula', 'serie'])->default('pelicula');
            $table->longText('json_data'); // Datos completos desde TMDB
            $table->enum('estado', ['activo', 'inactivo'])->default('activo'); // Soft delete con estado
            $table->string('override_titulo')->nullable(); // Título personalizado del admin
            $table->longText('override_sinopsis')->nullable(); // Sinopsis personalizada
            $table->string('override_image')->nullable(); // Imagen personalizada
            $table->timestamp('fecha_cache')->useCurrent();
            $table->timestamps();
            $table->softDeletes(); // Para soporte de eliminación suave
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache_tmdb');
    }
};
