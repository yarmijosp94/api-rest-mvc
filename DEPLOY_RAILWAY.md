# Guía Completa: Desplegar Laravel en Railway

## Requisitos Previos

- Cuenta en [GitHub](https://github.com)
- Cuenta en [Railway](https://railway.com)
- Git instalado en tu máquina
- Proyecto Laravel funcionando localmente

---

## Paso 1: Preparar el Proyecto

### 1.1 Crear Dockerfile

Crear archivo `Dockerfile` en la raíz del proyecto:

```dockerfile
FROM php:8.4-cli

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    nodejs \
    npm

# Limpiar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP
RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /app

# Copiar archivos de dependencias primero
COPY composer.json composer.lock ./
COPY package.json package-lock.json ./

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Instalar dependencias Node
RUN npm install

# Copiar el resto del código
COPY . .

# Ejecutar scripts de composer
RUN composer run-script post-autoload-dump || true

# Build de assets
RUN npm run build

# Puerto
EXPOSE 8080

# Comando de inicio
CMD php artisan config:clear && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
```

### 1.2 Crear Procfile (respaldo)

Crear archivo `Procfile` en la raíz del proyecto:

```
web: php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

### 1.3 Configurar HTTPS en Producción

Editar `app/Providers/AppServiceProvider.php`:

```php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Forzar HTTPS en producción
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
```

### 1.4 Verificar .gitignore

Asegurarse de que estos archivos estén ignorados:

```
.env
.env.backup
.env.production
/vendor
/node_modules
```

---

## Paso 2: Subir a GitHub

### 2.1 Inicializar Repositorio

```bash
git init
git add .
git commit -m "Initial commit - Laravel API"
```

### 2.2 Crear Repositorio en GitHub

1. Ir a https://github.com/new
2. Nombre del repositorio: `mi-proyecto-laravel`
3. Dejar vacío (sin README, sin .gitignore, sin licencia)
4. Click **"Create repository"**

### 2.3 Conectar y Subir

```bash
git remote add origin https://github.com/TU_USUARIO/mi-proyecto-laravel.git
git branch -M main
git push -u origin main
```

---

## Paso 3: Configurar Railway

### 3.1 Crear Proyecto

1. Ir a https://railway.com
2. Login con GitHub
3. Click **"New Project"**
4. Seleccionar **"Deploy from GitHub repo"**
5. Autorizar acceso al repositorio
6. Seleccionar tu repositorio

### 3.2 Agregar PostgreSQL

1. En el proyecto, click el botón **"+"** (arriba a la derecha)
2. Seleccionar **"Database"** → **"PostgreSQL"**
3. Railway creará automáticamente la base de datos

### 3.3 Configurar Variables de Entorno

Click en tu servicio (la app) → pestaña **"Variables"** → **"New Variable"**

| Variable | Valor |
|----------|-------|
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | `base64:...` (ver paso 3.4) |
| `APP_URL` | `https://tu-app.up.railway.app` |
| `DB_CONNECTION` | `pgsql` |
| `DATABASE_URL` | `${{Postgres.DATABASE_URL}}` |
| `SESSION_DRIVER` | `cookie` |
| `CACHE_STORE` | `file` |

### 3.4 Generar APP_KEY

Ejecutar localmente:

```bash
php artisan key:generate --show
```

Copiar el resultado (ejemplo: `base64:ABC123...`) y pegarlo en la variable `APP_KEY`.

### 3.5 Generar Dominio Público

1. Click en tu servicio
2. Ir a **"Settings"** → **"Networking"**
3. Click **"Generate Domain"**
4. Copiar la URL generada (ejemplo: `mi-app-production.up.railway.app`)
5. Actualizar la variable `APP_URL` con esta URL

---

## Paso 4: Desplegar

Railway desplegará automáticamente cuando:
- Hagas push a GitHub
- Cambies variables de entorno
- Hagas click en **"Deploy"** manualmente

### Ver Logs de Despliegue

1. Click en tu servicio
2. Click en el deployment activo
3. Ver pestaña **"Build Logs"** y **"Deploy Logs"**

---

## Paso 5: Verificar Funcionamiento

1. Abrir la URL de tu app: `https://tu-app.up.railway.app`
2. Verificar que las migraciones se ejecutaron en los logs
3. Probar el login/registro si aplica
4. Verificar que no hay errores de Mixed Content (HTTP/HTTPS)

---

## Solución de Problemas Comunes

### Error: Mixed Content (HTTP/HTTPS)

**Problema:** Los assets se cargan con HTTP en lugar de HTTPS.

**Solución:** Agregar en `AppServiceProvider.php`:

```php
if (config('app.env') === 'production') {
    URL::forceScheme('https');
}
```

### Error: Migraciones fallan con comandos de PostgreSQL

**Problema:** Las migraciones usan sintaxis específica de PostgreSQL (como `CREATE EXTENSION`).

**Solución:** Verificar el driver antes de ejecutar comandos específicos:

```php
if (DB::connection()->getDriverName() === 'pgsql') {
    DB::statement('CREATE EXTENSION IF NOT EXISTS "pgcrypto"');
}
```

### Error: Base de datos SQLite en lugar de PostgreSQL

**Problema:** La app usa SQLite durante el build.

**Solución:**
1. No cachear configuración durante el build
2. Usar `php artisan config:clear` al inicio del CMD
3. Asegurar que `DATABASE_URL` esté configurada

### Error: Composer dependencies require PHP 8.4

**Problema:** Railway usa PHP 8.2 por defecto pero las dependencias requieren 8.4.

**Solución:** Usar Dockerfile con `FROM php:8.4-cli` en lugar de nixpacks.

---

## Comandos Útiles de Railway CLI

### Instalar Railway CLI

```bash
# macOS
brew install railway

# npm
npm install -g @railway/cli
```

### Comandos Principales

```bash
# Login
railway login

# Inicializar proyecto
railway init

# Ver estado
railway status

# Ver logs
railway logs

# Desplegar
railway up

# Abrir proyecto en navegador
railway open

# Ver variables
railway variables

# Configurar variable
railway variables set VARIABLE=valor
```

---

## Estructura de Costos Railway

- **Free Tier:** $5 USD de crédito gratis por mes
- **Uso típico Laravel + PostgreSQL:** ~$3-5 USD/mes
- **Sin tarjeta de crédito:** Límite de ejecución de 500 horas/mes

---

## Resumen de Archivos Creados

| Archivo | Descripción |
|---------|-------------|
| `Dockerfile` | Configuración de contenedor Docker con PHP 8.4 |
| `Procfile` | Comando de inicio (respaldo) |
| `DEPLOY_RAILWAY.md` | Esta guía |

---

## Links de Referencia

- [Documentación Railway](https://docs.railway.com/)
- [Railway CLI](https://docs.railway.com/guides/cli)
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [PHP Docker Images](https://hub.docker.com/_/php)

---

**Autor:** Yoselyn Lemas
**Fecha:** Enero 2026
**Proyecto:** API REST MVC - Sistema de Facturación
