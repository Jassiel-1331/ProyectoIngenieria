<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificaciones extends Model
{
    use HasFactory;

    protected $table = 'calificaciones';
    protected $primaryKey = 'id_calificacion';
    public $timestamps = true;

    protected $fillable = [
        'id_usuario',
        'id_tmdb',
        'calificacion',
        'comentario',
        'fecha_calificacion'
    ];

    protected $casts = [
        'fecha_calificacion' => 'datetime'
    ];

    /**
     * Relación: Una calificación pertenece a un usuario
     */
    public function usuario() {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Relación: Una calificación pertenece a una película
     */
    public function pelicula() {
        return $this->belongsTo(Cache_tmdb::class, 'id_tmdb', 'id_tmdb');
    }
}