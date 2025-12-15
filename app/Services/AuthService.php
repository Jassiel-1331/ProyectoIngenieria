<?php 

namespace App\Services;

use App\Models\Usuario;

class AuthService{

public function login($correo, $contrasena){

    $user = Usuario::where('correo', $correo)->first();
    
    if(!$user || !password_verify($contrasena, $user->contrasena_hash)){
        return ['success' => false, 'message' => 'Credenciales inválidas'];
    }

    session(['user_id' => $user->id_usuario]);

    return [
        'success' => true,
        'message' => 'Login exitoso',
        'user' => $user
    ];
}





}
