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

# Cachear configuración Laravel
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true

# Puerto
EXPOSE 8080

# Comando de inicio
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
