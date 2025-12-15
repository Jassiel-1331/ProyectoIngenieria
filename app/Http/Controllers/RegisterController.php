<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RegisterService;

class RegisterController extends Controller
{
    
    public function register(Request $request, RegisterService $registerService)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuarios,correo',
            'contrasena' => 'required|string|min:6'
        ]);

        $resultado = $registerService->register($request);

        if(!$resultado['success']){
            return response()->json(['message' => $resultado['message']], 400);
        }

        return response()->json([
            'message' => $resultado['message'],
            'usuario' => $resultado['usuario']
        ], 201);
    }
}


}