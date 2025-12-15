<?php

namespace Database\Seeders;

use App\Models\Usuario;
use App\Models\Genero;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario admin de ejemplo (opcional)
        Usuario::firstOrCreate(
            ['correo' => 'admin@cineclip.com'],
            [
                'nombre' => 'Administrador',
                'contrasena_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                'rol' => 'admin'
            ]
        );

        // Crear géneros básicos (opcional)
        $generos = [
            'Acción',
            'Comedia',
            'Drama',
            'Terror',
            'Romántica',
            'Ciencia Ficción',
            'Aventura',
            'Animación'
        ];

        foreach ($generos as $nombre) {
            Genero::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
