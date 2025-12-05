<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioControllers;
use App\Http\Middleware;



Route::post('/login', [UsuarioController::class, 'login']);
Route::post('/logout', [UsuarioController::class, 'logout']);

Route::middleware('auth.user')->group(function () {
    
    Route::post('/logout', [UsuarioController::class, 'logout']);

    Route::get('/profile', function () {
        return response()->json(['message'=>'Perfil de usuario autenticado',
        'user_id'=>session('user_id')],200);
    });

});

