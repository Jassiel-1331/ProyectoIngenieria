<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreferenciaUsuario extends Model
{
    use HasFactory;

    protected $table = 'preferencias_usuario';
    protected $primaryKey = 'id_preferencia';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'id_genero',
        'fecha_preferencia'
    ];

    protected $casts = [
        'fecha_preferencia' => 'datetime'
    ];

    /**
     * Relación: Una preferencia pertenece a un usuario
     */
    public function usuario() {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Relación: Una preferencia pertenece a un género
     */
    public function genero() {
        return $this->belongsTo(Genero::class, 'id_genero', 'id_genero');
    }
}
