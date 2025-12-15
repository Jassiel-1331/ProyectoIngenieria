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
        Schema::create('generos', function (Blueprint $table) {
            $table->id('id_genero');
            $table->string('nombre')->unique(); // Acción, Comedia, Drama, etc.
            $table->timestamps();
        });

        Schema::create('preferencias_usuario', function (Blueprint $table) {
            $table->id('id_preferencia');
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario')->onDelete('cascade');
            $table->foreignId('id_genero')->constrained('generos', 'id_genero')->onDelete('cascade');
            $table->timestamp('fecha_preferencia')->useCurrent();
            
            // Evitar preferencias duplicadas
            $table->unique(['id_usuario', 'id_genero']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preferencias_usuario');
        Schema::dropIfExists('generos');
    }
};
