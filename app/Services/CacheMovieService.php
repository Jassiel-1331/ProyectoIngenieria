<?php 

namespace App\Services;

use App\Models\Cache_tmdb;

class CacheMovieService {

    /**
     * Cachear una película/serie desde TMDB
     * @param string $tipo 'pelicula' o 'serie'
     * @param array $datos Datos JSON de TMDB
     * @return Cache_tmdb
     */
    public function cachear($tipo, $datos) {
        
        // Verificar si ya existe en caché
        $idTmdb = $datos['id'] ?? null;
        
        if(!$idTmdb) {
            throw new \Exception('ID de TMDB no proporcionado');
        }

        $peliculaEnCache = Cache_tmdb::where('id_tmdb', $idTmdb)->first();
        
        if($peliculaEnCache) {
            // Actualizar datos si ya existe
            $peliculaEnCache->update([
                'tipo' => $tipo,
                'json_data' => json_encode($datos),
                'estado' => 'activo'
            ]);
            return $peliculaEnCache;
        }

        // Crear nuevo registro en caché
        $nuevaPelicula = Cache_tmdb::create([
            'id_tmdb' => $idTmdb,
            'tipo' => $tipo,
            'json_data' => json_encode($datos),
            'estado' => 'activo'
        ]);

        return $nuevaPelicula;
    }

    /**
     * Marcar película como inactiva (eliminación lógica)
     * @param int $idTmdb
     * @return bool
     */
    public function desactivarPelicula($idTmdb) {
        $pelicula = Cache_tmdb::find($idTmdb);
        
        if(!$pelicula) {
            throw new \Exception('Película no encontrada en caché');
        }

        return $pelicula->update(['estado' => 'inactivo']);
    }

    /**
     * Reactivar película eliminada
     * @param int $idTmdb
     * @return bool
     */
    public function reactivarPelicula($idTmdb) {
        $pelicula = Cache_tmdb::find($idTmdb);
        
        if(!$pelicula) {
            throw new \Exception('Película no encontrada en caché');
        }

        return $pelicula->update(['estado' => 'activo']);
    }

    /**
     * Obtener todas las películas activas
     * @return Collection
     */
    public function obtenerActivas($tipo = null) {
        $query = Cache_tmdb::where('estado', 'activo');
        
        if($tipo) {
            $query->where('tipo', $tipo);
        }
        
        return $query->get();
    }

    /**
     * Obtener película del caché por ID
     * @param int $idTmdb
     * @return Cache_tmdb|null
     */
    public function obtenerPorId($idTmdb) {
        return Cache_tmdb::find($idTmdb);
    }

    /**
     * Actualizar datos personalizados de película (overrides del admin)
     * @param int $idTmdb
     * @param array $overrides
     * @return Cache_tmdb
     */
    public function actualizarOverrides($idTmdb, $overrides) {
        $pelicula = Cache_tmdb::find($idTmdb);
        
        if(!$pelicula) {
            throw new \Exception('Película no encontrada en caché');
        }

        $datosActualizar = [];
        
        if(isset($overrides['titulo'])) {
            $datosActualizar['override_titulo'] = $overrides['titulo'];
        }
        if(isset($overrides['sinopsis'])) {
            $datosActualizar['override_sinopsis'] = $overrides['sinopsis'];
        }
        if(isset($overrides['imagen'])) {
            $datosActualizar['override_image'] = $overrides['imagen'];
        }

        if(!empty($datosActualizar)) {
            $pelicula->update($datosActualizar);
        }

        return $pelicula;
    }

    /**
     * Limpiar caché de películas inactivas
     * @return int Número de registros eliminados
     */
    public function limpiarInactivos() {
        return Cache_tmdb::where('estado', 'inactivo')->forceDelete();
    }
}


