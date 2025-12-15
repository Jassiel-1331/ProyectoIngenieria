<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calificaciones;
use App\Models\Cache_tmdb;

class CalificacionesController extends Controller
{
    /**
     * Crear o actualizar calificación de película
     * POST /api/calificaciones
     */
    public function store(Request $request)
    {
        $userId = session('user_id');

        $request->validate([
            'id_tmdb' => 'required|exists:cache_tmdb,id_tmdb',
            'calificacion' => 'required|integer|min:1|max:10',
            'comentario' => 'nullable|string|max:500'
        ]);

        // Verificar que la película existe y está activa
        $pelicula = Cache_tmdb::find($request->id_tmdb);
        if(!$pelicula || $pelicula->estado !== 'activo') {
            return response()->json([
                'message' => 'La película no existe o está inactiva'
            ], 404);
        }

        // Buscar calificación existente
        $calificacion = Calificaciones::where('id_usuario', $userId)
            ->where('id_tmdb', $request->id_tmdb)
            ->first();

        if($calificacion) {
            // Actualizar
            $calificacion->update([
                'calificacion' => $request->calificacion,
                'comentario' => $request->comentario
            ]);
            $mensaje = 'Calificación actualizada exitosamente';
        } else {
            // Crear nueva
            $calificacion = Calificaciones::create([
                'id_usuario' => $userId,
                'id_tmdb' => $request->id_tmdb,
                'calificacion' => $request->calificacion,
                'comentario' => $request->comentario,
                'fecha_calificacion' => now()
            ]);
            $mensaje = 'Calificación registrada exitosamente';
        }

        return response()->json([
            'message' => $mensaje,
            'calificacion' => $calificacion
        ], $calificacion->wasRecentlyCreated ? 201 : 200);
    }

    /**
     * Obtener calificaciones de una película
     * GET /api/calificaciones/pelicula/{idTmdb}
     */
    public function obtenerPorPelicula($idTmdb)
    {
        $calificaciones = Calificaciones::where('id_tmdb', $idTmdb)
            ->with('usuario:id_usuario,nombre')
            ->orderBy('fecha_calificacion', 'desc')
            ->get();

        return response()->json([
            'message' => 'Calificaciones obtenidas exitosamente',
            'total' => $calificaciones->count(),
            'calificaciones' => $calificaciones
        ], 200);
    }

    /**
     * Obtener mis calificaciones
     * GET /api/calificaciones/usuario
     */
    public function obtenerMisCalificaciones()
    {
        $userId = session('user_id');

        $calificaciones = Calificaciones::where('id_usuario', $userId)
            ->with('pelicula')
            ->orderBy('fecha_calificacion', 'desc')
            ->get();

        return response()->json([
            'message' => 'Tus calificaciones',
            'total' => $calificaciones->count(),
            'calificaciones' => $calificaciones
        ], 200);
    }
}
