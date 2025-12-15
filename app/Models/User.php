<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'correo',
        'contrasena_hash',
        'rol',
        'fecha_registro'
    ];

    protected $hidden = [
        'contrasena_hash' // No enviar la contraseña en respuestas JSON
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
    ];

    /**
     * Relación: Un usuario puede tener muchas calificaciones
     */
    public function calificaciones() {
        return $this->hasMany(Calificaciones::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Relación: Un usuario puede tener muchas preferencias de géneros
     */
    public function preferenciasGenero() {
        return $this->hasMany(PreferenciaUsuario::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Relación: Obtener los géneros del usuario (through)
     */
    public function generosFavoritos() {
        return $this->belongsToMany(
            Genero::class,
            'preferencias_usuario',
            'id_usuario',
            'id_genero',
            'id_usuario',
            'id_genero'
        );
    }

    /**
     * Verificar si el usuario es administrador
     */
    public function esAdmin() {
        return $this->rol === 'admin';
    }
}
