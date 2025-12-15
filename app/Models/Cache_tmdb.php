<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cache_tmdb extends Model{

    use HasFactory, SoftDeletes;

    protected $table = 'cache_tmdb';
    protected $primaryKey = 'id_tmdb';
    public $timestamps = true;

    protected $fillable = [
        'id_tmdb',
        'tipo',
        'json_data',
        'estado',
        'override_titulo',
        'override_sinopsis',
        'override_image'
    ];

    protected $casts = [
        'fecha_cache' => 'datetime',
        'json_data' => 'array' // Convertir JSON automáticamente a array
    ];

    /**
     * Relación: Una película puede tener muchas calificaciones
     */
    public function calificaciones() {
        return $this->hasMany(Calificaciones::class, 'id_tmdb', 'id_tmdb');
    }

    /**
     * Obtener el título (priorizar override)
     */
    public function getTitulo() {
        return $this->override_titulo ?? ($this->json_data['title'] ?? $this->json_data['name'] ?? 'Sin título');
    }

    /**
     * Obtener la sinopsis (priorizar override)
     */
    public function getSinopsis() {
        return $this->override_sinopsis ?? ($this->json_data['overview'] ?? 'Sin descripción');
    }

    /**
     * Obtener la imagen (priorizar override)
     */
    public function getImagen() {
        return $this->override_image ?? ($this->json_data['poster_path'] ?? null);
    }

    /**
     * Obtener calificación promedio
     */
    public function getCalificacionPromedio() {
        $calificaciones = $this->calificaciones();
        if($calificaciones->count() === 0) {
            return 0;
        }
        return round($calificaciones->avg('calificacion'), 1);
    }
}