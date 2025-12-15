<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Services\AuthService;
use App\Services\RegisterService;

class UsuarioController extends Controller
{
    // LOGIN
    public function login(Request $request, AuthService $auth)
    {
        $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required'
        ]);

        $resultado = $auth->login(
            $request->correo,
            $request->contrasena
        );
        
        if(!$resultado['success']){
            return response()->json(['message' => $resultado['message']], 401);
        }

        return response()->json([
            'message' => $resultado['message'],
            'user' => $resultado['user']
        ], 200);
    }

    // LOGOUT
    public function logout(Request $request)
    {
        $request->session()->forget('user_id');

        return response()->json(['message' => 'Logout exitoso'], 200);
    }

    // REGISTER
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
