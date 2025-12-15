<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Usuario;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $userId = session('user_id');
        
        if(!$userId) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        $usuario = Usuario::find($userId);
        
        if(!$usuario || !$usuario->esAdmin()) {
            return response()->json(['message' => 'No tienes permisos de administrador'], 403);
        }

        return $next($request);
    }
}
