<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cache_tmdb;
use App\Services\CacheMovieService;

class MovieController extends Controller
{


   public const ERROR_CASE = 'No tienes permisos para esta acción';

    protected $cacheService;

    public function __construct(CacheMovieService $cacheService)
    {
        $this->cacheService  =$cacheService;
    }

    /**
     * Obtener listado de películas/series activas
     * GET /api/movies?tipo=pelicula
     */
    public function index(Request $request)
    {
        $tipo = $request->query('tipo'); // pelicula o serie
        
        $peliculas = $this->cacheService->obtenerActivas($tipo);

        return response()->json([
            'message' => 'Películas obtenidas exitosamente',
            'total' => $peliculas->count(),
            'peliculas' => $peliculas
        ], 200);
    }

    /**
     * Guardar película/serie en caché
     * POST /api/movies
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:pelicula,serie',
            'json_data' => 'required|array'
        ]);

        try {
            $pelicula = $this->cacheService->cachear(
                $request->tipo,
                $request->json_data
            );

            return response()->json([
                'message' => 'Película guardada en caché exitosamente',
                'pelicula' => $pelicula
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cachear película',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Obtener película específica
     * GET /api/movies/{id}
     */
    public function show(string $id)
    {
        $pelicula = $this->cacheService->obtenerPorId($id);

        if(!$pelicula) {
            return response()->json([
                'message' => 'Película no encontrada'
            ], 404);
        }

        // Agregar métodos útiles
        $pelicula->calificacion_promedio = $pelicula->getCalificacionPromedio();
        $pelicula->titulo = $pelicula->getTitulo();
        $pelicula->sinopsis = $pelicula->getSinopsis();
        $pelicula->imagen = $pelicula->getImagen();

        return response()->json([
            'message' => 'Película obtenida exitosamente',
            'pelicula' => $pelicula
        ], 200);
    }

    /**
     * Actualizar datos personalizados de película (ADMIN)
     * PATCH /api/movies/{id}
     */
    public function update(Request $request, string $id)
    {
        // Verificar que sea admin
        if(!$this->esAdmin()) {
            return response()->json([
                'message' => self::ERROR_CASE
            ], 403);
        }

        $request->validate([
            'titulo' => 'string|max:255',
            'sinopsis' => 'string',
            'imagen' => 'string|url'
        ]);

        try {
            $pelicula = $this->cacheService->actualizarOverrides($id, $request->all());

            return response()->json([
                'message' => 'Película actualizada exitosamente',
                'pelicula' => $pelicula
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar película',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Eliminar película (cambiar estado a inactivo - ADMIN)
     * DELETE /api/movies/{id}
     */
    public function destroy(string $id)
    {
        // Verificar que sea admin
        if(!$this->esAdmin()) {
            return response()->json([
                'message' => self::ERROR_CASE
            ], 403);
        }

        try {
            $this->cacheService->desactivarPelicula($id);

            return response()->json([
                'message' => 'Película desactivada exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al desactivar película',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Reactivar película eliminada (ADMIN)
     * PATCH /api/movies/{id}/reactivar
     */
    public function reactivar(string $id)
    {
        // Verificar que sea admin
        if(!$this->esAdmin()) {
            return response()->json([
                'message' => self::ERROR_CASE
            ], 403);
        }

        try {
            $this->cacheService->reactivarPelicula($id);

            return response()->json([
                'message' => 'Película reactivada exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al reactivar película',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Verificar si el usuario autenticado es admin
     */
    private function esAdmin() {
        $userId = session('user_id');
        if(!$userId){} return false;
        
        $usuario = \App\Models\Usuario::find($userId);
        return $usuario && $usuario->esAdmin();
    }
}

