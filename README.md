# CineClip API - Plataforma de Recomendación de Películas/Series

<p align="center">
  <strong>API RESTful desarrollada en Laravel para una plataforma de recomendación de películas y series</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=flat-square" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat-square" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0+-00758F?style=flat-square" alt="MySQL">
  <img src="https://img.shields.io/badge/API-REST-09A3D5?style=flat-square" alt="REST API">
  <img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="License">
</p>

---

## 📋 Descripción

**CineClip** es una API REST completa desarrollada en **Laravel** para una plataforma de recomendación de películas y series. Permite a los usuarios registrarse, autenticarse, explorar películas en caché, realizar calificaciones, establecer preferencias de género y recibir recomendaciones personalizadas.

Los administradores pueden gestionar el contenido: agregar películas al caché, editarlas (con overrides de título, sinopsis e imagen) y desactivarlas de forma lógica (sin eliminar los datos).

---

## ✨ Características Principales

### ✅ Autenticación y Autorización
- Registro e inicio de sesión de usuarios
- Sistema de roles (usuario estándar / administrador)
- Gestión de sesiones con Laravel
- Middleware de autenticación y autorización

### ✅ Gestión de Películas en Caché
- Almacenamiento de películas/series desde TMDB
- **Eliminación lógica**: cambiar estado a "inactivo" (sin perder datos)
- Reactivación de películas
- Overrides personalizados: título, sinopsis, imagen
- Cálculo automático de calificación promedio

### ✅ Sistema de Calificaciones
- Los usuarios pueden calificar películas (1-10)
- Comentarios opcionales
- Una calificación por película por usuario (única)
- Visualización de calificaciones de películas

### ✅ Preferencias de Género
- Selección de géneros favoritos
- Agregar/eliminar preferencias
- Base para recomendaciones personalizadas

### ✅ API RESTful Completa
- 20+ endpoints bien documentados
- Validaciones de entrada robustas
- Respuestas JSON estructuradas
- Códigos HTTP semánticos

---

## 🚀 Stack Tecnológico

| Categoría | Tecnología |
|-----------|-----------|
| **Backend** | Laravel 11 |
| **Lenguaje** | PHP 8.1+ |
| **Base de Datos** | MySQL 8.0+ |
| **Autenticación** | Sessions (Laravel) |
| **ORM** | Eloquent |
| **API** | REST con JSON |
| **Validaciones** | Laravel Validator |

---

## 📦 Instalación

### Requisitos Previos
- PHP 8.1 o superior
- MySQL 8.0 o superior
- Composer
- Git (opcional)

### Pasos de Instalación

```bash
# 1. Clonar o descargar el proyecto
git clone <url-repositorio>
cd cineclip_back

# 2. Instalar dependencias
composer install

# 3. Copiar archivo de configuración
cp .env.example .env

# 4. Generar clave de aplicación
php artisan key:generate

# 5. Crear base de datos
# Abre MySQL y ejecuta:
# CREATE DATABASE cineclip_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# 6. Actualizar .env con credenciales de BD
# DB_HOST=127.0.0.1
# DB_DATABASE=cineclip_db
# DB_USERNAME=root
# DB_PASSWORD=tu_contraseña

# 7. Ejecutar migraciones
php artisan migrate

# 8. Iniciar servidor
php artisan serve

# ✅ API disponible en http://localhost:8000/api
```

Para instrucciones detalladas, ver [INSTALACION_Y_DEPLOYMENT.md](INSTALACION_Y_DEPLOYMENT.md)

---

## 📚 Documentación

| Documento | Descripción |
|-----------|------------|
| [API_DOCUMENTATION.md](API_DOCUMENTATION.md) | Documentación completa de endpoints con ejemplos |
| [ARQUITECTURA.md](ARQUITECTURA.md) | Diagramas y estructura del proyecto |
| [TESTING_CHECKLIST.md](TESTING_CHECKLIST.md) | Guía de pruebas de API |
| [RESUMEN_IMPLEMENTACION.md](RESUMEN_IMPLEMENTACION.md) | Resumen de lo implementado |
| [INSTALACION_Y_DEPLOYMENT.md](INSTALACION_Y_DEPLOYMENT.md) | Guía de instalación y deployment |

---

## 🔌 Endpoints Principales

### Autenticación
```
POST   /api/register      - Registrar nuevo usuario
POST   /api/login         - Iniciar sesión
POST   /api/logout        - Cerrar sesión
GET    /api/profile       - Obtener perfil (protegido)
```

### Películas (Públicas)
```
GET    /api/movies        - Listar películas/series
GET    /api/movies/{id}   - Obtener película específica
```

### Películas (Admin)
```
POST   /api/movies                    - Crear película en caché
PATCH  /api/movies/{id}               - Actualizar datos película
DELETE /api/movies/{id}               - Desactivar película
PATCH  /api/movies/{id}/reactivar     - Reactivar película
```

### Géneros
```
GET    /api/generos       - Listar géneros disponibles
```

### Preferencias (Protegidas)
```
GET    /api/mi-preferencias           - Obtener mis preferencias
POST   /api/preferencias              - Agregar preferencia
DELETE /api/preferencias/{idGenero}   - Eliminar preferencia
```

### Calificaciones (Protegidas)
```
POST   /api/calificaciones                    - Crear/actualizar calificación
GET    /api/calificaciones/usuario            - Mis calificaciones
GET    /api/calificaciones/pelicula/{idTmdb}  - Calificaciones de película
```

---

## 💾 Estructura de Base de Datos

```
Tablas:
├── usuarios                 (Información de usuarios)
├── cache_tmdb              (Películas en caché)
├── calificaciones          (Valoraciones de usuarios)
├── generos                 (Géneros disponibles)
└── preferencias_usuario    (Preferencias de usuario)

Relaciones:
├── Usuario → Calificaciones (1:N)
├── Usuario → Preferencias (1:N)
├── Cache_tmdb → Calificaciones (1:N)
├── Genero → Preferencias (1:N)
└── Usuario ↔ Genero (N:N through Preferencias)
```

---

## 📂 Estructura de Carpetas

```
cineclip_back/
├── app/
│   ├── Http/
│   │   ├── Controllers/     (Lógica de endpoints)
│   │   └── Middleware/      (Seguridad y autenticación)
│   ├── Models/              (Modelos Eloquent)
│   └── Services/            (Lógica de negocio)
├── database/
│   ├── migrations/          (Estructura de BD)
│   └── seeders/             (Datos iniciales)
├── routes/
│   ├── api.php              (Rutas API)
│   └── web.php              (Rutas web)
├── bootstrap/
│   └── app.php              (Configuración app)
├── config/                  (Archivos de configuración)
└── storage/
    └── logs/                (Logs de aplicación)
```

---

## 🔐 Seguridad

### Implementado
- ✅ Hashing de contraseñas con `password_hash()`
- ✅ Validación de entrada robusta
- ✅ Middleware de autenticación
- ✅ Middleware de autorización (admin)
- ✅ Eliminación lógica de datos (no física)
- ✅ Relaciones de base de datos con integridad referencial
- ✅ CORS configurable

### En Producción
```env
APP_DEBUG=false
APP_ENV=production
```

---

## 🧪 Pruebas

### Requisitos
- Postman o Insomnia
- Cliente HTTP (curl)

### Prueba Rápida
```bash
# Registro
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"nombre":"Juan","correo":"juan@test.com","contrasena":"123456"}'

# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"correo":"juan@test.com","contrasena":"123456"}'

# Ver películas
curl http://localhost:8000/api/movies
```

Para pruebas completas, ver [TESTING_CHECKLIST.md](TESTING_CHECKLIST.md)

---

## 🚀 Deployment

### Local
```bash
php artisan serve
# http://localhost:8000
```

### Producción (VPS)
```bash
# Ver INSTALACION_Y_DEPLOYMENT.md para:
# - Configuración Nginx/Apache
# - SSL/HTTPS con Let's Encrypt
# - Monitoreo y logs
# - Backups automáticos
```

### Heroku, AWS, Azure, DigitalOcean
Ver [INSTALACION_Y_DEPLOYMENT.md](INSTALACION_Y_DEPLOYMENT.md) para instrucciones específicas

---

## 📋 Requisitos del Proyecto

Este proyecto cumple con todos los requisitos del **Proyecto Final - Desarrollo de Software VII** de la **Universidad Tecnológica de Panamá**:

- [x] **Autenticación de usuarios**: Registro e inicio de sesión
- [x] **Roles de usuario**: Usuario estándar y administrador
- [x] **Manejo de sesiones**: Sesiones de Laravel
- [x] **Cookies**: Para personalización (configurable)
- [x] **Base de datos**: Estructura normalizada con relaciones
- [x] **Formulario de preferencias**: Selección de géneros via API
- [x] **Sistema de recomendaciones**: Base implementada (por género)
- [x] **Administrador**: CRUD completo de películas
- [x] **Webservices**: JSON/XML (JSON por defecto)

---

## 📊 Métricas del Proyecto

| Métrica | Cantidad |
|---------|----------|
| **Modelos** | 5 |
| **Controllers** | 4 |
| **Services** | 3 |
| **Middleware** | 2 |
| **Migraciones** | 7 |
| **Endpoints** | 20+ |
| **Lineas de código** | ~2000+ |

---

## 🔧 Comandos Útiles

```bash
# Ejecutar migraciones
php artisan migrate

# Limpiar caché
php artisan cache:clear

# Ver rutas
php artisan route:list

# Entrar a consola (tinker)
php artisan tinker

# Ejecutar tests
php artisan test

# Optimizar para producción
composer dump-autoload --optimize
php artisan config:cache
php artisan route:cache
```

---

## 📝 Cambios Realizados

### Servicios Corregidos
- ✅ `AuthService.php` - Lógica de login funcional
- ✅ `RegisterService.php` - Registro con validaciones
- ✅ `CacheMovieService.php` - Gestión completa de caché

### Controllers Creados/Corregidos
- ✅ `UsuarioController.php` - Endpoints de autenticación
- ✅ `MovieController.php` - CRUD de películas
- ✅ `CalificacionesController.php` - Calificaciones
- ✅ `GeneroController.php` - Géneros y preferencias

### Modelos Creados/Corregidos
- ✅ `Usuario.php` - Con relaciones
- ✅ `Cache_tmdb.php` - Películas en caché
- ✅ `Calificaciones.php` - Valoraciones
- ✅ `Genero.php` - Géneros
- ✅ `PreferenciaUsuario.php` - Preferencias

### Migraciones
- ✅ Tabla `usuarios` (personalizada)
- ✅ Tabla `cache_tmdb` (películas)
- ✅ Tabla `calificaciones` (valoraciones)
- ✅ Tabla `generos` y `preferencias_usuario`

### Rutas y Middleware
- ✅ Routes en `routes/api.php` actualizadas
- ✅ Middleware `AuthUser.php` funcional
- ✅ Middleware `AdminMiddleware.php` creado
- ✅ Registrados en `bootstrap/app.php`

---

## ⚡ Rendimiento

### Optimizaciones Implementadas
- Lazy loading de relaciones con Eloquent
- Consultas eficientes con selects específicos
- Índices en tablas principales
- Caché de configuración
- JSON casting automático

---

## 🤝 Contribuciones

Este es un proyecto educativo para la Universidad Tecnológica de Panamá. Las contribuciones son bienvenidas a través de pull requests.

---

## 📄 Licencia

Este proyecto está bajo la licencia MIT. Ver `LICENSE` para más detalles.

---

## 👨‍💻 Desarrollado por

**Proyecto Final - Desarrollo de Software VII**  
Universidad Tecnológica de Panamá  
Facultad de Ingeniería de Sistemas Computacionales  
Departamento de Programación de Computadoras

---

## 📞 Soporte

Para problemas o preguntas:
1. Revisar la documentación en los archivos `.md`
2. Consultar `TESTING_CHECKLIST.md` para verificar funcionalidad
3. Ver `ARQUITECTURA.md` para entender el flujo

---

## ✅ Estado

**Proyecto**: ✅ COMPLETADO Y LISTO PARA PRODUCCIÓN

- [x] Autenticación funcional
- [x] Login y registro corregidos
- [x] Gestión de películas implementada
- [x] Sistema de calificaciones completo
- [x] Preferencias de usuario funcionales
- [x] Documentación completa
- [x] Pruebas verificadas
- [x] Seguridad implementada

---

<p align="center">
  <strong>Desarrollado con ❤️ usando Laravel</strong>
</p>


If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
>>>>>>> 28ad604 (Commit inicial para la implemenracion dela api laravel. CINECLIP)
