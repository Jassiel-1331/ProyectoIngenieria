<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genero;
use App\Models\PreferenciaUsuario;

class GeneroController extends Controller
{
    /**
     * Obtener todos los géneros
     * GET /api/generos
     */
    public function index()
    {
        $generos = Genero::all();

        return response()->json([
            'message' => 'Géneros obtenidos exitosamente',
            'total' => $generos->count(),
            'generos' => $generos
        ], 200);
    }

    /**
     * Obtener mis preferencias de género
     * GET /api/mi-preferencias
     */
    public function misPreferencias()
    {
        $userId = session('user_id');

        $preferencias = PreferenciaUsuario::where('id_usuario', $userId)
            ->with('genero')
            ->orderBy('fecha_preferencia', 'desc')
            ->get();

        return response()->json([
            'message' => 'Tus preferencias de género',
            'total' => $preferencias->count(),
            'generos' => $preferencias->pluck('genero')
        ], 200);
    }

    /**
     * Agregar preferencia de género
     * POST /api/preferencias
     */
    public function agregarPreferencia(Request $request)
    {
        $userId = session('user_id');

        $request->validate([
            'id_genero' => 'required|exists:generos,id_genero'
        ]);

        // Verificar que no exista ya
        $existente = PreferenciaUsuario::where('id_usuario', $userId)
            ->where('id_genero', $request->id_genero)
            ->first();

        if($existente) {
            return response()->json([
                'message' => 'Ya tienes esta preferencia'
            ], 400);
        }

        $preferencia = PreferenciaUsuario::create([
            'id_usuario' => $userId,
            'id_genero' => $request->id_genero,
            'fecha_preferencia' => now()
        ]);

        return response()->json([
            'message' => 'Preferencia agregada exitosamente',
            'preferencia' => $preferencia
        ], 201);
    }

    /**
     * Eliminar preferencia de género
     * DELETE /api/preferencias/{idGenero}
     */
    public function eliminarPreferencia($idGenero)
    {
        $userId = session('user_id');

        $eliminado = PreferenciaUsuario::where('id_usuario', $userId)
            ->where('id_genero', $idGenero)
            ->delete();

        if(!$eliminado) {
            return response()->json([
                'message' => 'Preferencia no encontrada'
            ], 404);
        }

        return response()->json([
            'message' => 'Preferencia eliminada exitosamente'
        ], 200);
    }
}
