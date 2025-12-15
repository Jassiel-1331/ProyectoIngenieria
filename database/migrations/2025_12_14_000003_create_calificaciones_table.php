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
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->id('id_calificacion');
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario')->onDelete('cascade');
            $table->unsignedBigInteger('id_tmdb')->constrained('cache_tmdb', 'id_tmdb')->onDelete('cascade');
            $table->unsignedTinyInteger('calificacion'); // 1-10 o 1-5
            $table->longText('comentario')->nullable();
            $table->timestamp('fecha_calificacion')->useCurrent();
            $table->timestamps();
            
            // Asegurar que un usuario solo pueda calificar una vez por película
            $table->unique(['id_usuario', 'id_tmdb']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calificaciones');
    }
};
