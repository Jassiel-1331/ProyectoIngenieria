<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthUser
{
    
    public function handle(Request $request, Closure $next): Response
    {

        if(!session('user_id')){
            return response()->json(['message'=>'No autenticado'],401);
        }
        return $next($request);
    }
}
