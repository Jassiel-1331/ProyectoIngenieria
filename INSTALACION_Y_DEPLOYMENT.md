# GUÍA DE INSTALACIÓN Y CONFIGURACIÓN - CineClip API

## ✅ Requisitos Previos

- **PHP**: 8.1 o superior
- **Composer**: Última versión
- **MySQL**: 8.0 o superior
- **Node.js**: 16+ (opcional, para build assets)
- **Git**: Última versión

### Verificar instalación
```bash
php --version
composer --version
mysql --version
```

---

## 🚀 INSTALACIÓN LOCAL

### Paso 1: Clonar o Descargar Proyecto
```bash
# Si está en Git
git clone <url-repositorio>
cd cineclip_back

# O si ya descargó el archivo
# Extraer el zip y navegar a la carpeta
cd cineclip_back
```

### Paso 2: Instalar Dependencias
```bash
composer install
```

### Paso 3: Configurar Variables de Entorno
```bash
# Copiar archivo de ejemplo
cp .env.example .env

# Editar .env con tus datos
# (Abre .env en tu editor preferido)
```

#### Configuración .env recomendada:
```env
APP_NAME=CineClip
APP_ENV=local
APP_KEY=  # Se genera automáticamente
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de Datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cineclip_db
DB_USERNAME=root
DB_PASSWORD=  # Tu contraseña MySQL

# Sesiones
SESSION_DRIVER=cookie
SESSION_LIFETIME=120

# Caché
CACHE_DRIVER=file

# Colas
QUEUE_CONNECTION=sync

# Mail (opcional)
MAIL_DRIVER=log
```

### Paso 4: Generar Clave de Aplicación
```bash
php artisan key:generate
```

**Salida esperada**: `Application key set successfully.`

### Paso 5: Crear Base de Datos
```bash
# Opción 1: Desde terminal MySQL
mysql -u root -p
CREATE DATABASE cineclip_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Opción 2: Usar tu cliente MySQL preferido (MySQL Workbench, phpMyAdmin, etc.)
```

### Paso 6: Ejecutar Migraciones
```bash
php artisan migrate
```

**Salida esperada**: Se crearán todas las tablas necesarias

### Paso 7: Crear Usuario Admin (Opcional)
```bash
php artisan tinker

# En la consola Tinker:
$user = App\Models\Usuario::create([
    'nombre' => 'Admin',
    'correo' => 'admin@cineclip.com',
    'contrasena_hash' => password_hash('admin123', PASSWORD_DEFAULT),
    'rol' => 'admin'
]);

exit
```

### Paso 8: Iniciar Servidor
```bash
php artisan serve
```

**Salida esperada**: `Started Laravel development server on http://127.0.0.1:8000`

### ✅ La API está lista en: `http://localhost:8000/api`

---

## 📝 Estructura de Comandos Útiles

```bash
# Ejecutar migraciones
php artisan migrate

# Deshacer última migración
php artisan migrate:rollback

# Deshacer todas las migraciones
php artisan migrate:reset

# Ejecutar migraciones + seeders
php artisan migrate --seed

# Ver rutas registradas
php artisan route:list

# Ejecutar tests
php artisan test

# Entrar a consola interactiva
php artisan tinker

# Limpiar caché
php artisan cache:clear

# Regenerar autoload
composer dump-autoload
```

---

## 🧪 PRUEBAS RÁPIDAS

### Prueba 1: Registro
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Juan Pérez",
    "correo": "juan@test.com",
    "contrasena": "password123"
  }'
```

### Prueba 2: Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "correo": "juan@test.com",
    "contrasena": "password123"
  }'
```

### Prueba 3: Ver Películas
```bash
curl -X GET http://localhost:8000/api/movies
```

### Mejor: Usar Postman o Insomnia
1. Descargar **Postman** o **Insomnia**
2. Importar colección (si está disponible)
3. Configurar variables de entorno
4. Ejecutar requests

---

## 🐛 RESOLUCIÓN DE PROBLEMAS

### Error: "Class not found"
```bash
composer dump-autoload
php artisan cache:clear
```

### Error: "No application encryption key"
```bash
php artisan key:generate
```

### Error: "SQLSTATE HY000 [2002]"
- Verificar que MySQL esté corriendo
- Verificar credenciales en .env
- Verificar que la BD existe

### Error: "Access denied for user"
```bash
# En .env, actualizar:
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### Puertos en conflicto
```bash
# Laravel usa puerto 8000 por defecto
# Para usar otro puerto:
php artisan serve --port=8001
```

### Problema con sesiones
```bash
# Limpiar sesiones
php artisan session:clear

# Regenerar sesiones
php artisan config:cache
```

---

## 📦 DEPLOYMENT EN PRODUCCIÓN

### Opción 1: Hosting Compartido (cPanel/Plesk)

#### Preparar proyecto
```bash
# En tu máquina local
composer install --no-dev

# Crear .env.production
cp .env .env.production
# Editar con credenciales de producción
```

#### Subir archivos
1. Comprimir proyecto: `zip -r cineclip.zip . -x "vendor/*" ".git/*" "node_modules/*"`
2. Subir a hosting via FTP/SCP
3. Descomprimir en servidor
4. Instalar dependencias: `composer install --no-dev`
5. Generar key: `php artisan key:generate`
6. Ejecutar migraciones: `php artisan migrate --force`

### Opción 2: Heroku
```bash
# 1. Instalar Heroku CLI
# 2. Crear aplicación
heroku create mi-app-cineclip

# 3. Configurar variables
heroku config:set APP_KEY=$(php artisan key:generate --show)
heroku config:set APP_ENV=production
heroku config:set DB_HOST=... DB_NAME=... etc

# 4. Crear Procfile
echo "web: vendor/bin/heroku-php-apache2 public/" > Procfile

# 5. Hacer push
git push heroku main
```

### Opción 3: AWS/Azure/DigitalOcean (VPS)

#### Configuración Básica
```bash
# 1. SSH al servidor
ssh user@ip_address

# 2. Instalar dependencias
sudo apt update
sudo apt install -y php-fpm php-mysql php-mbstring php-xml php-json
sudo apt install -y mysql-server
sudo apt install -y nginx
sudo apt install -y composer

# 3. Clonar repositorio
git clone <url> /var/www/cineclip
cd /var/www/cineclip

# 4. Instalar dependencias PHP
composer install --no-dev

# 5. Configurar .env
cp .env.example .env
# Editar con credenciales de BD

# 6. Generar key
php artisan key:generate

# 7. Ejecutar migraciones
php artisan migrate --force

# 8. Configurar Nginx
# (Ver sección Nginx más abajo)

# 9. Reiniciar servicios
sudo systemctl restart nginx
sudo systemctl restart php8.1-fpm
```

### Nginx Configuration
Crear archivo `/etc/nginx/sites-available/cineclip`:

```nginx
server {
    listen 80;
    server_name tu-dominio.com;
    root /var/www/cineclip/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Activar sitio:
```bash
sudo ln -s /etc/nginx/sites-available/cineclip /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### SSL/HTTPS (Let's Encrypt)
```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot certbot --nginx -d tu-dominio.com
```

---

## 📊 MONITOREO EN PRODUCCIÓN

### Verificar logs
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Nginx logs
sudo tail -f /var/log/nginx/access.log
sudo tail -f /var/log/nginx/error.log

# MySQL logs
sudo tail -f /var/log/mysql/error.log
```

### Comandos útiles en producción
```bash
# Optimizar autoload
composer dump-autoload --optimize

# Cache de configuración
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Limpiar caché
php artisan cache:clear
php artisan view:clear

# Queue (si usas colas)
php artisan queue:work --daemon

# Backup base de datos
mysqldump -u user -p database_name > backup.sql

# Restaurar base de datos
mysql -u user -p database_name < backup.sql
```

---

## 🔐 SEGURIDAD EN PRODUCCIÓN

```php
// En .env
APP_DEBUG=false  // NUNCA true en producción
APP_ENV=production

// En config/app.php, ensure:
'debug' => env('APP_DEBUG', false),

// Contraseñas seguras en .env
DB_PASSWORD=xxxxxxxxxxxxxx  // Contraseña fuerte
APP_KEY=base64:xxxxxxxxxxxx // Generada automáticamente

// CORS (si es necesario)
// En config/cors.php configurar allowed origins
```

### Checklist de Seguridad
- [ ] APP_DEBUG=false
- [ ] APP_ENV=production
- [ ] HTTPS/SSL habilitado
- [ ] Firewall configurado
- [ ] Actualizaciones de servidor aplicadas
- [ ] Logs monitoreados
- [ ] Backups automáticos
- [ ] Rate limiting configurado
- [ ] Headers de seguridad
- [ ] SQL Injection prevención (usar ORM)

---

## 📈 ESCALABILIDAD

### Para manejar más usuarios
1. **Caché**: Implementar Redis
2. **Colas**: Usar job queues para operaciones pesadas
3. **Base de datos**: Considerar replicación
4. **CDN**: Para archivos estáticos
5. **Load Balancer**: Para distribuir tráfico
6. **Microservicios**: Separar funcionalidades

---

## 📞 SOPORTE Y DOCUMENTACIÓN

- Documentación oficial Laravel: https://laravel.com/docs
- API Documentation: Ver `API_DOCUMENTATION.md` en el proyecto
- Testing Guide: Ver `TESTING_CHECKLIST.md`
- Arquitectura: Ver `ARQUITECTURA.md`

---

## ✅ CHECKLIST FINAL

- [ ] PHP 8.1+ instalado
- [ ] MySQL 8.0+ instalado
- [ ] Composer instalado
- [ ] Dependencias instaladas (`composer install`)
- [ ] .env configurado
- [ ] APP_KEY generado
- [ ] Base de datos creada
- [ ] Migraciones ejecutadas
- [ ] Servidor iniciado (`php artisan serve`)
- [ ] API respondiendo en `http://localhost:8000/api`
- [ ] Pruebas funcionales completadas

---

**¡Proyecto listo para usar!** 🚀

Para más información, consulta los archivos .md incluidos en el proyecto.
