<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    // LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required'
        ]);

        $user = Usuario::where('correo', $request->correo)->first();

        if (!$user || !password_verify($request->contrasena, $user->contrasena_hash)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        session(['user_id' => $user->id_usuario]);

        return response()->json([
            'message' => 'Login exitoso',
            'usuario' => $user
        ], 200);
    }


    // LOGOUT
    public function logout(Request $request)
    {
        $request->session()->forget('user_id');

        return response()->json(['message' => 'Logout exitoso'], 200);
    }


    // REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuarios,correo',
            'contrasena' => 'required|string|min:6'
        ]);

        $hashed_password = password_hash($request->contrasena, PASSWORD_DEFAULT);

        $user = Usuario::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'contrasena_hash' => $hashed_password,
            'rol' => 'usuario',
            'fecha_registro' => now(),
        ]);

        return response()->json([
            'message' => 'Registro exitoso',
            'usuario' => $user
        ], 201);
    }
}
