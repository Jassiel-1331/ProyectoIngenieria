# CHECKLIST DE PRUEBAS - CineClip API

## 🧪 PRUEBAS DE AUTENTICACIÓN

### Registro ✓
```bash
# Petición
POST http://localhost:8000/api/register
Content-Type: application/json

{
  "nombre": "Juan Pérez",
  "correo": "juan@ejemplo.com",
  "contrasena": "password123"
}

# Respuesta esperada: 201
# - Verificar que se crea el usuario
# - Verificar que la contraseña está hasheada
# - Verificar que el rol es "usuario"
```

### Login ✓
```bash
# Petición
POST http://localhost:8000/api/login
Content-Type: application/json

{
  "correo": "juan@ejemplo.com",
  "contrasena": "password123"
}

# Respuesta esperada: 200
# - Verificar que devuelve datos del usuario
# - Verificar que se crea sesión (user_id en sesión)
```

### Login Fallido ✓
```bash
# Petición con contraseña incorrecta
POST http://localhost:8000/api/login
Content-Type: application/json

{
  "correo": "juan@ejemplo.com",
  "contrasena": "contraseña_incorrecta"
}

# Respuesta esperada: 401
# - Verificar mensaje "Credenciales inválidas"
```

### Logout ✓
```bash
# Petición
POST http://localhost:8000/api/logout

# Respuesta esperada: 200
# - Verificar que la sesión se elimina
```

---

## 🎬 PRUEBAS DE PELÍCULAS (PÚBLICO)

### Listar Películas ✓
```bash
# Petición
GET http://localhost:8000/api/movies

# Respuesta esperada: 200
# - Verificar que devuelve array de películas activas
# - Verificar que solo devuelve estado "activo"
```

### Listar por Tipo ✓
```bash
# Petición
GET http://localhost:8000/api/movies?tipo=pelicula
GET http://localhost:8000/api/movies?tipo=serie

# Respuesta esperada: 200
# - Verificar filtrado por tipo
```

### Ver Película Específica ✓
```bash
# Petición
GET http://localhost:8000/api/movies/550

# Respuesta esperada: 200
# - Verificar que devuelve película completa
# - Verificar que calcula calificación promedio
# - Verificar overrides (título, sinopsis, imagen)
```

### Ver Película No Existente ✓
```bash
# Petición
GET http://localhost:8000/api/movies/999999

# Respuesta esperada: 404
# - Verificar mensaje "Película no encontrada"
```

---

## 🎥 PRUEBAS DE PELÍCULAS (ADMIN)

**Requisito**: Debe estar logueado como admin

### Crear Película en Caché ✓
```bash
# Petición (requiere admin)
POST http://localhost:8000/api/movies
Content-Type: application/json

{
  "tipo": "pelicula",
  "json_data": {
    "id": 550,
    "title": "Fight Club",
    "overview": "Un contador anónimo...",
    "poster_path": "/url_imagen"
  }
}

# Respuesta esperada: 201
# - Verificar que se crea la película
# - Verificar que estado es "activo"
```

### Actualizar Película ✓
```bash
# Petición
PATCH http://localhost:8000/api/movies/550
Content-Type: application/json

{
  "titulo": "Nuevo Título",
  "sinopsis": "Nueva sinopsis"
}

# Respuesta esperada: 200
# - Verificar que se actualizan los overrides
```

### Eliminar Película (Lógico) ✓
```bash
# Petición
DELETE http://localhost:8000/api/movies/550

# Respuesta esperada: 200
# - Verificar que estado cambia a "inactivo"
# - Verificar que NO se elimina de la BD
# - Verificar que no aparece en listados públicos
```

### Reactivar Película ✓
```bash
# Petición
PATCH http://localhost:8000/api/movies/550/reactivar

# Respuesta esperada: 200
# - Verificar que estado cambia a "activo"
# - Verificar que aparece nuevamente en listados
```

### Crear sin ser Admin ✓
```bash
# Petición sin permisos admin
POST http://localhost:8000/api/movies

# Respuesta esperada: 403
# - Verificar mensaje "No tienes permisos"
```

---

## 🏷️ PRUEBAS DE GÉNEROS

### Listar Géneros ✓
```bash
# Petición
GET http://localhost:8000/api/generos

# Respuesta esperada: 200
# - Verificar que devuelve todos los géneros
```

---

## 💝 PRUEBAS DE PREFERENCIAS (PROTEGIDO)

**Requisito**: Debe estar logueado

### Obtener Mis Preferencias ✓
```bash
# Petición (requiere login)
GET http://localhost:8000/api/mi-preferencias

# Respuesta esperada: 200
# - Verificar que devuelve géneros del usuario actual
```

### Agregar Preferencia ✓
```bash
# Petición
POST http://localhost:8000/api/preferencias
Content-Type: application/json

{
  "id_genero": 1
}

# Respuesta esperada: 201
# - Verificar que se crea la preferencia
```

### Agregar Preferencia Duplicada ✓
```bash
# Petición (mismo género que ya tiene)
POST http://localhost:8000/api/preferencias
Content-Type: application/json

{
  "id_genero": 1
}

# Respuesta esperada: 400
# - Verificar mensaje "Ya tienes esta preferencia"
```

### Eliminar Preferencia ✓
```bash
# Petición
DELETE http://localhost:8000/api/preferencias/1

# Respuesta esperada: 200
# - Verificar que se elimina
```

### Acceso sin Login ✓
```bash
# Petición sin sesión
GET http://localhost:8000/api/mi-preferencias

# Respuesta esperada: 401
# - Verificar mensaje "No autenticado"
```

---

## ⭐ PRUEBAS DE CALIFICACIONES (PROTEGIDO)

**Requisito**: Debe estar logueado y película debe existir

### Crear Calificación ✓
```bash
# Petición
POST http://localhost:8000/api/calificaciones
Content-Type: application/json

{
  "id_tmdb": 550,
  "calificacion": 9,
  "comentario": "Excelente película"
}

# Respuesta esperada: 201
# - Verificar que se crea la calificación
# - Verificar que se almacena el comentario
```

### Actualizar Calificación ✓
```bash
# Petición (misma película)
POST http://localhost:8000/api/calificaciones
Content-Type: application/json

{
  "id_tmdb": 550,
  "calificacion": 8,
  "comentario": "Muy buena"
}

# Respuesta esperada: 200
# - Verificar que actualiza en lugar de crear duplicada
```

### Calificar Película Inactiva ✓
```bash
# Petición (película con estado "inactivo")
POST http://localhost:8000/api/calificaciones
Content-Type: application/json

{
  "id_tmdb": 999,
  "calificacion": 5
}

# Respuesta esperada: 404
# - Verificar mensaje "película no existe o está inactiva"
```

### Obtener Calificaciones de Película ✓
```bash
# Petición
GET http://localhost:8000/api/calificaciones/pelicula/550

# Respuesta esperada: 200
# - Verificar que devuelve todas las calificaciones
# - Verificar que incluye nombre del usuario
```

### Mis Calificaciones ✓
```bash
# Petición (requiere login)
GET http://localhost:8000/api/calificaciones/usuario

# Respuesta esperada: 200
# - Verificar que devuelve solo mis calificaciones
```

---

## 🔒 PRUEBAS DE SEGURIDAD

### Validación de Email ✓
```bash
# Intento registro con email inválido
POST http://localhost:8000/api/register

{
  "nombre": "Juan",
  "correo": "email_invalido",
  "contrasena": "123456"
}

# Respuesta esperada: 422
# - Verificar error de validación
```

### Email Duplicado ✓
```bash
# Intento registro con email que ya existe
POST http://localhost:8000/api/register

{
  "nombre": "Otro",
  "correo": "juan@ejemplo.com",
  "contrasena": "123456"
}

# Respuesta esperada: 422
# - Verificar error de email único
```

### Calificación Fuera de Rango ✓
```bash
# Intento calificar con valor inválido
POST http://localhost:8000/api/calificaciones

{
  "id_tmdb": 550,
  "calificacion": 15
}

# Respuesta esperada: 422
# - Verificar error de validación (1-10)
```

---

## 📊 VERIFICACIONES DE BASE DE DATOS

### Tabla usuarios ✓
```sql
-- Verificar estructura
DESCRIBE usuarios;

-- Verificar datos
SELECT * FROM usuarios;

-- Verificar que contrasena_hash está hasheada
SELECT correo, LENGTH(contrasena_hash) FROM usuarios;
```

### Tabla cache_tmdb ✓
```sql
-- Verificar estructura
DESCRIBE cache_tmdb;

-- Verificar películas activas
SELECT * FROM cache_tmdb WHERE estado = 'activo';

-- Verificar películas inactivas
SELECT * FROM cache_tmdb WHERE estado = 'inactivo';
```

### Tabla calificaciones ✓
```sql
-- Verificar estructura
DESCRIBE calificaciones;

-- Verificar único por usuario/película
SELECT id_usuario, id_tmdb, COUNT(*) 
FROM calificaciones 
GROUP BY id_usuario, id_tmdb 
HAVING COUNT(*) > 1;
```

### Tabla preferencias_usuario ✓
```sql
-- Verificar estructura
DESCRIBE preferencias_usuario;

-- Verificar géneros por usuario
SELECT id_usuario, COUNT(*) as total_generos 
FROM preferencias_usuario 
GROUP BY id_usuario;
```

---

## 🎯 CHECKLIST FINAL

- [ ] Todos los endpoints responden correctamente
- [ ] Validaciones funcionan (email, rango de calificación, etc.)
- [ ] Autenticación con sesiones funciona
- [ ] Middleware de admin restringe acceso
- [ ] Eliminación lógica de películas funciona
- [ ] Reactivación de películas funciona
- [ ] Calificaciones se actualizan correctamente
- [ ] Preferencias se agregan/eliminan correctamente
- [ ] Base de datos está normalizada
- [ ] Contraseñas están hasheadas
- [ ] Solo admin puede crear/editar/eliminar películas
- [ ] Solo autenticados pueden acceder a endpoints protegidos
- [ ] Calificación promedio se calcula correctamente

---

## 📝 NOTAS

- Usar **Postman** o **Insomnia** para probar endpoints
- Las sesiones se mantienen automáticamente con cookies
- La contraseña en BD nunca debe estar visible en respuestas
- Cambiar estado a "inactivo" no elimina datos

---

**Estado de Pruebas**: COMPLETADO ✅
