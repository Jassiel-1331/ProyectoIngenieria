# API CineClip - Plataforma de Recomendación de Películas/Series

## Descripción
API REST desarrollada en Laravel para una plataforma de recomendación de películas y series con autenticación de usuarios, gestión de contenido en caché, calificaciones y preferencias personalizadas.

---

## Características Implementadas

### ✅ Autenticación y Usuarios
- **Registro de usuarios** con validación de email único
- **Login/Logout** con sesiones
- **Roles de usuario**: `usuario` (estándar) y `admin` (administrador)
- **Contraseñas hasheadas** con `password_hash()` (PASSWORD_DEFAULT)

### ✅ Gestión de Películas/Series en Caché
- **Almacenamiento en base de datos** desde TMDB (API externa)
- **Eliminación lógica**: cambiar estado a "inactivo" (no elimina datos)
- **Overrides del admin**: título, sinopsis e imagen personalizados
- **Calificación promedio** automática

### ✅ Sistema de Calificaciones
- Los usuarios pueden **calificar películas** (1-10)
- **Comentarios opcionales**
- Una calificación por película por usuario (único)

### ✅ Preferencias de Género
- Los usuarios pueden **seleccionar géneros favoritos**
- Sistema de **recomendaciones basadas en géneros**

### ✅ Sistema de Roles
- **Admin**: Puede agregar, editar, eliminar películas
- **Usuario**: Acceso a ver películas, calificar, establecer preferencias

---

## Estructura de Base de Datos

### Tablas Principales
- **usuarios**: Almacena información de usuarios
- **cache_tmdb**: Películas/series en caché desde TMDB
- **calificaciones**: Valoraciones de usuarios
- **generos**: Géneros de películas
- **preferencias_usuario**: Géneros favoritos de cada usuario

---

## Endpoints API

### 1. AUTENTICACIÓN

#### Registro
```http
POST /api/register
Content-Type: application/json

{
  "nombre": "Juan Pérez",
  "correo": "juan@ejemplo.com",
  "contrasena": "123456"
}
```

**Respuesta (201)**
```json
{
  "message": "Registro exitoso",
  "usuario": {
    "id_usuario": 1,
    "nombre": "Juan Pérez",
    "correo": "juan@ejemplo.com",
    "rol": "usuario"
  }
}
```

#### Login
```http
POST /api/login
Content-Type: application/json

{
  "correo": "juan@ejemplo.com",
  "contrasena": "123456"
}
```

**Respuesta (200)**
```json
{
  "message": "Login exitoso",
  "user": {
    "id_usuario": 1,
    "nombre": "Juan Pérez",
    "rol": "usuario"
  }
}
```

#### Logout
```http
POST /api/logout
```

**Respuesta (200)**
```json
{
  "message": "Logout exitoso"
}
```

---

### 2. PELÍCULAS/SERIES (PÚBLICO)

#### Listar Películas
```http
GET /api/movies?tipo=pelicula
```

**Parámetros:**
- `tipo` (opcional): `pelicula` o `serie`

**Respuesta (200)**
```json
{
  "message": "Películas obtenidas exitosamente",
  "total": 5,
  "peliculas": [
    {
      "id_tmdb": 550,
      "tipo": "pelicula",
      "estado": "activo",
      "json_data": {...},
      "override_titulo": "Título personalizado",
      "calificacion_promedio": 8.5
    }
  ]
}
```

#### Ver Película Específica
```http
GET /api/movies/550
```

**Respuesta (200)**
```json
{
  "message": "Película obtenida exitosamente",
  "pelicula": {
    "id_tmdb": 550,
    "titulo": "Fight Club",
    "sinopsis": "Un contador anónimo...",
    "imagen": "url_imagen",
    "calificacion_promedio": 8.5
  }
}
```

---

### 3. PELÍCULAS/SERIES (ADMIN - PROTEGIDO)

#### Crear/Cachear Película
```http
POST /api/movies
Content-Type: application/json
Authorization: Session

{
  "tipo": "pelicula",
  "json_data": {
    "id": 550,
    "title": "Fight Club",
    "overview": "Un contador anónimo...",
    "poster_path": "/url_imagen"
  }
}
```

**Respuesta (201)**
```json
{
  "message": "Película guardada en caché exitosamente",
  "pelicula": {...}
}
```

#### Actualizar Datos de Película
```http
PATCH /api/movies/550
Content-Type: application/json
Authorization: Session

{
  "titulo": "Nuevo Título",
  "sinopsis": "Nueva sinopsis",
  "imagen": "url_nueva_imagen"
}
```

**Respuesta (200)**
```json
{
  "message": "Película actualizada exitosamente",
  "pelicula": {...}
}
```

#### Eliminar Película (Cambiar a Inactivo)
```http
DELETE /api/movies/550
Authorization: Session
```

**Respuesta (200)**
```json
{
  "message": "Película desactivada exitosamente"
}
```

#### Reactivar Película
```http
PATCH /api/movies/550/reactivar
Authorization: Session
```

**Respuesta (200)**
```json
{
  "message": "Película reactivada exitosamente"
}
```

---

### 4. GÉNEROS (PÚBLICO)

#### Listar Géneros
```http
GET /api/generos
```

**Respuesta (200)**
```json
{
  "message": "Géneros obtenidos exitosamente",
  "total": 8,
  "generos": [
    {
      "id_genero": 1,
      "nombre": "Acción"
    },
    {
      "id_genero": 2,
      "nombre": "Comedia"
    }
  ]
}
```

---

### 5. PREFERENCIAS DE USUARIO (PROTEGIDO)

#### Mis Preferencias
```http
GET /api/mi-preferencias
Authorization: Session
```

**Respuesta (200)**
```json
{
  "message": "Tus preferencias de género",
  "total": 2,
  "generos": [
    {
      "id_genero": 1,
      "nombre": "Acción"
    },
    {
      "id_genero": 4,
      "nombre": "Drama"
    }
  ]
}
```

#### Agregar Preferencia
```http
POST /api/preferencias
Content-Type: application/json
Authorization: Session

{
  "id_genero": 1
}
```

**Respuesta (201)**
```json
{
  "message": "Preferencia agregada exitosamente",
  "preferencia": {...}
}
```

#### Eliminar Preferencia
```http
DELETE /api/preferencias/1
Authorization: Session
```

**Respuesta (200)**
```json
{
  "message": "Preferencia eliminada exitosamente"
}
```

---

### 6. CALIFICACIONES (PROTEGIDO)

#### Crear/Actualizar Calificación
```http
POST /api/calificaciones
Content-Type: application/json
Authorization: Session

{
  "id_tmdb": 550,
  "calificacion": 9,
  "comentario": "Excelente película"
}
```

**Respuesta (201)**
```json
{
  "message": "Calificación registrada exitosamente",
  "calificacion": {
    "id_calificacion": 1,
    "id_usuario": 1,
    "id_tmdb": 550,
    "calificacion": 9,
    "comentario": "Excelente película"
  }
}
```

#### Calificaciones de Película
```http
GET /api/calificaciones/pelicula/550
```

**Respuesta (200)**
```json
{
  "message": "Calificaciones obtenidas exitosamente",
  "total": 3,
  "calificaciones": [
    {
      "id_calificacion": 1,
      "usuario": {
        "nombre": "Juan Pérez"
      },
      "calificacion": 9,
      "comentario": "Excelente"
    }
  ]
}
```

#### Mis Calificaciones
```http
GET /api/calificaciones/usuario
Authorization: Session
```

**Respuesta (200)**
```json
{
  "message": "Tus calificaciones",
  "total": 2,
  "calificaciones": [...]
}
```

---

## Migración de Base de Datos

Para ejecutar las migraciones:

```bash
php artisan migrate
```

Migraciones incluidas:
- `0001_01_01_000000_create_users_table` - Tabla de usuarios Laravel
- `0001_01_01_000001_create_cache_table` - Caché de Laravel
- `0001_01_01_000002_create_jobs_table` - Colas de trabajo
- `2025_12_14_000001_create_usuarios_table` - Tabla personalizada de usuarios
- `2025_12_14_000002_create_cache_tmdb_table` - Películas en caché
- `2025_12_14_000003_create_calificaciones_table` - Calificaciones
- `2025_12_14_000004_create_generos_and_preferencias_table` - Géneros y preferencias

---

## Modelos y Relaciones

### Usuario
- `hasMany` Calificaciones
- `hasMany` PreferenciaUsuario
- `belongsToMany` Genero (through PreferenciaUsuario)

### Cache_tmdb
- `hasMany` Calificaciones

### Calificaciones
- `belongsTo` Usuario
- `belongsTo` Cache_tmdb

### PreferenciaUsuario
- `belongsTo` Usuario
- `belongsTo` Genero

---

## Autenticación

La API utiliza **sesiones** de Laravel para mantener la autenticación.

**Middleware requerido:**
- `auth.user`: Verifica que el usuario esté autenticado
- `admin`: Verifica que el usuario sea administrador

---

## Códigos de Estado HTTP

- `200`: OK - Solicitud exitosa
- `201`: Created - Recurso creado
- `400`: Bad Request - Datos inválidos
- `401`: Unauthorized - No autenticado
- `403`: Forbidden - Sin permisos
- `404`: Not Found - Recurso no encontrado
- `500`: Internal Server Error - Error del servidor

---

## Ejemplo Completo: Flujo de Usuario

1. **Registro**
   ```bash
   POST /api/register
   ```

2. **Login**
   ```bash
   POST /api/login
   ```

3. **Ver películas**
   ```bash
   GET /api/movies
   ```

4. **Agregar preferencias**
   ```bash
   POST /api/preferencias
   ```

5. **Calificar película**
   ```bash
   POST /api/calificaciones
   ```

---

## Instalación y Setup

```bash
# 1. Instalar dependencias
composer install

# 2. Crear archivo .env
cp .env.example .env

# 3. Generar clave de aplicación
php artisan key:generate

# 4. Ejecutar migraciones
php artisan migrate

# 5. Iniciar servidor
php artisan serve
```

---

## Notas Importantes

- Las contraseñas se hashean automáticamente con `password_hash()`
- La eliminación de películas es **lógica** (cambio de estado), no física
- El caché de películas almacena datos JSON de TMDB
- Los overrides del admin se pueden aplicar a título, sinopsis e imagen
- Las sesiones se mantienen automáticamente en Laravel

---

**Desarrollado para**: Proyecto Final - Desarrollo de Software VII  
**Universidad**: Tecnológica de Panamá
