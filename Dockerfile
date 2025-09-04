# Imagen base PHP-FPM
FROM php:8.2.12-fpm

WORKDIR /var/www/html

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    npm \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    zlib1g-dev \
    pkg-config \
    build-essential \
    default-libmysqlclient-dev \
    libmariadb-dev \
    libmariadb-dev-compat \
    libpq-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP necesarias (una por una para depurar)
# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    npm \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    zlib1g-dev \
    pkg-config \
    build-essential \
    autoconf \
    libc-dev \
    default-libmysqlclient-dev \
    libmariadb-dev \
    libmariadb-dev-compat \
    libpq-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar proyecto
COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Compilar assets de Vite
RUN npm install && npm run build

# Dar permisos
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Exponer puerto
EXPOSE 8000

# Ejecutar Laravel en primer plano
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]