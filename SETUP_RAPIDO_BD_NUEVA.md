# GUÍA RÁPIDA - Configurar Base de Datos Nueva

## ✅ TODO FUNCIONA DESDE CERO

El proyecto está completamente listo para usar con una base de datos nueva. Aquí te muestro los pasos:

---

## 🚀 Pasos Rápidos (5 minutos)

### 1️⃣ Configurar .env
```bash
# Copiar archivo de ejemplo
cp .env.example .env

# Editar .env y cambiar:
DB_DATABASE=cineclip_db
DB_USERNAME=root
DB_PASSWORD=tu_contraseña_mysql
```

### 2️⃣ Crear Base de Datos
```bash
# Abre MySQL y ejecuta:
mysql -u root -p
CREATE DATABASE cineclip_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 3️⃣ Generar Key (Si no lo hiciste)
```bash
php artisan key:generate
```

### 4️⃣ Ejecutar Migraciones
```bash
# Esto crea todas las tablas automáticamente
php artisan migrate
```

**Output esperado:**
```
Migration table created successfully.
Migrated: 0001_01_01_000000_create_users_table
Migrated: 0001_01_01_000001_create_cache_table
Migrated: 0001_01_01_000002_create_jobs_table
Migrated: 2025_11_16_231110_create_personal_access_tokens_table
Migrated: 2025_12_14_000001_create_usuarios_table
Migrated: 2025_12_14_000002_create_cache_tmdb_table
Migrated: 2025_12_14_000003_create_calificaciones_table
Migrated: 2025_12_14_000004_create_generos_and_preferencias_table

✅ 8 migrations completed successfully
```

### 5️⃣ Ejecutar Seeders (Opcional)
```bash
# Crear usuario admin (admin@cineclip.com / admin123)
# y géneros básicos automáticamente
php artisan db:seed
```

### 6️⃣ Iniciar Servidor
```bash
php artisan serve
```

**Listo en: http://localhost:8000/api** ✅

---

## 🧪 Verificar que Todo Funciona

### Test 1: Registrar Usuario
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Juan Pérez",
    "correo": "juan@test.com",
    "contrasena": "password123"
  }'
```

**Respuesta esperada (201):**
```json
{
  "message": "Usuario registrado correctamente",
  "usuario": {
    "id_usuario": 1,
    "nombre": "Juan Pérez",
    "correo": "juan@test.com",
    "rol": "usuario"
  }
}
```

### Test 2: Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "correo": "juan@test.com",
    "contrasena": "password123"
  }'
```

**Respuesta esperada (200):**
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

### Test 3: Ver Películas (vacío)
```bash
curl http://localhost:8000/api/movies
```

**Respuesta esperada (200):**
```json
{
  "message": "Películas obtenidas exitosamente",
  "total": 0,
  "peliculas": []
}
```

---

## 📊 Estructura de Base de Datos Creada

```
Tablas creadas automáticamente:

usuarios
├─ id_usuario (PK)
├─ nombre
├─ correo (UNIQUE)
├─ contrasena_hash
├─ rol (usuario/admin)
└─ fecha_registro

cache_tmdb
├─ id_tmdb (PK)
├─ tipo (pelicula/serie)
├─ json_data
├─ estado (activo/inactivo)
├─ override_titulo
├─ override_sinopsis
├─ override_image
└─ timestamps

calificaciones
├─ id_calificacion (PK)
├─ id_usuario (FK → usuarios)
├─ id_tmdb (FK → cache_tmdb)
├─ calificacion (1-10)
├─ comentario
└─ UNIQUE(id_usuario, id_tmdb)

generos
├─ id_genero (PK)
├─ nombre (UNIQUE)
└─ timestamps

preferencias_usuario
├─ id_preferencia (PK)
├─ id_usuario (FK → usuarios)
├─ id_genero (FK → generos)
└─ UNIQUE(id_usuario, id_genero)
```

---

## 🔑 Datos Iniciales (Si ejecutaste db:seed)

**Usuario Admin:**
- Email: `admin@cineclip.com`
- Contraseña: `admin123`
- Rol: `admin`

**Géneros:**
- Acción
- Comedia
- Drama
- Terror
- Romántica
- Ciencia Ficción
- Aventura
- Animación

---

## ⚠️ Errores Comunes y Soluciones

### "SQLSTATE HY000 [2002]"
**Problema**: MySQL no está corriendo

**Solución**:
```bash
# Windows: Iniciar MySQL
net start MySQL80

# Linux
sudo service mysql start

# macOS
brew services start mysql
```

### "Unknown database 'cineclip_db'"
**Problema**: No creaste la base de datos

**Solución**:
```bash
mysql -u root -p
CREATE DATABASE cineclip_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### "Access denied for user"
**Problema**: Credenciales incorrectas en .env

**Solución**: Actualiza .env con usuario/contraseña correctos:
```env
DB_USERNAME=root
DB_PASSWORD=tu_contraseña
```

### "Base [table] table does not exist"
**Problema**: Las migraciones no se ejecutaron

**Solución**:
```bash
php artisan migrate
```

### "Migration ... already exists"
**Problema**: Las migraciones ya se ejecutaron (normal)

**Solución**: Simplemente ignora el mensaje o usa:
```bash
php artisan migrate:status  # Ver estado
```

---

## 🔄 Comandos Útiles

```bash
# Ver estado de migraciones
php artisan migrate:status

# Revertir última migración
php artisan migrate:rollback

# Revertir todas las migraciones
php artisan migrate:reset

# Migraciones + seeders
php artisan migrate --seed

# Ejecutar solo seeders
php artisan db:seed

# Limpiar base de datos completamente
php artisan migrate:refresh

# Limpiar y sembrar datos nuevos
php artisan migrate:refresh --seed

# Entrar a consola interactiva
php artisan tinker
```

---

## 📝 Notas Importantes

✅ **No hay datos hardcodeados** - Está todo limpio  
✅ **Migraciones en orden correcto** - Sin problemas de dependencias  
✅ **Foreign keys completos** - Integridad referencial garantizada  
✅ **Cascading deletes** - Si eliminas usuario, se eliminan sus datos  
✅ **Timestamps automáticos** - Se establecen solos  
✅ **Soft deletes para películas** - No se pierden datos  
✅ **Validaciones en lugar** - Input validation en todos los endpoints  

---

## 🎯 Próximos Pasos (Después de Setup)

1. **Crear usuario admin en Postman**:
   - POST `/api/register`
   - Email: `admin@cineclip.com`
   - Rol se puede cambiar en BD

2. **Cargar películas**:
   - POST `/api/movies` (admin)
   - json_data desde TMDB API

3. **Crear géneros**:
   - POST `/api/generos` (admin)
   - O usar los del seeder

4. **Usar la API**:
   - Registrar usuario
   - Login
   - Ver películas
   - Calificar
   - Agregar preferencias

---

## ✨ ¿Listo?

Sigue estos pasos y en **5 minutos** tendrás:
- ✅ Base de datos funcional
- ✅ Tablas creadas
- ✅ API lista para usar
- ✅ Usuario admin (opcional)
- ✅ Géneros básicos (opcional)

**¡Comienza a desarrollar!** 🚀
