<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\CalificacionesController;
use App\Http\Controllers\GeneroController;

Route::post('/register', [UsuarioController::class, 'register']);
Route::post('/login', [UsuarioController::class, 'login']);
Route::post('/logout', [UsuarioController::class, 'logout'])->middleware('auth.user');

Route::middleware('auth.user')->group(function () {
    Route::get('/profile', function () {
        return response()->json([
            'message' => 'Perfil de usuario autenticado',
            'user_id' => session('user_id')
        ], 200);
    });
});

Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movies/{id}', [MovieController::class, 'show']);

Route::middleware(['auth.user', 'admin'])->group(function () {
    Route::post('/movies', [MovieController::class, 'store']);
    Route::patch('/movies/{id}', [MovieController::class, 'update']);
    Route::delete('/movies/{id}', [MovieController::class, 'destroy']);
    Route::patch('/movies/{id}/reactivar', [MovieController::class, 'reactivar']);
});

Route::get('/generos', [GeneroController::class, 'index']);

Route::middleware('auth.user')->group(function () {
    Route::get('/mi-preferencias', [GeneroController::class, 'misPreferencias']);
    Route::post('/preferencias', [GeneroController::class, 'agregarPreferencia']);
    Route::delete('/preferencias/{idGenero}', [GeneroController::class, 'eliminarPreferencia']);

    Route::post('/calificaciones', [CalificacionesController::class, 'store']);
    Route::get('/calificaciones/pelicula/{idTmdb}', [CalificacionesController::class, 'obtenerPorPelicula']);
    Route::get('/calificaciones/usuario', [CalificacionesController::class, 'obtenerMisCalificaciones']);
});


