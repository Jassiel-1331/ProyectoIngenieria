<?php

namespace App\Services;

use App\Models\Usuario;
use Illuminate\Http\Request;

class RegisterService{

    public function register(Request $request){
         
        $usuarioExistente = Usuario::where('correo', $request->correo)->first();
        
        if($usuarioExistente){
            return [
                'success' => false,
                'message' => 'El usuario ya existe'      
            ];
        }

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'contrasena_hash' => password_hash($request->contrasena, PASSWORD_DEFAULT),
            'rol' => 'usuario',
            'fecha_registro' => now()
        ]);

        return [
            'success' => true,
            'message' => 'Usuario registrado correctamente',
            'usuario' => $usuario
        ];
    }



}