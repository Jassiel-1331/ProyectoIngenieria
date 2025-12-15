<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genero extends Model
{
    use HasFactory;

    protected $table = 'generos';
    protected $primaryKey = 'id_genero';
    public $timestamps = true;

    protected $fillable = [
        'nombre'
    ];

    /**
     * Relación: Un género puede tener muchas preferencias de usuarios
     */
    public function preferenciaUsuarios() {
        return $this->hasMany(PreferenciaUsuario::class, 'id_genero', 'id_genero');
    }
}
