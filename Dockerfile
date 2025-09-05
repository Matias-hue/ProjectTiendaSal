# ===========================
# Stage 1: Build frontend assets con Node 20
# ===========================
FROM node:20 AS build

WORKDIR /app

# Copiar solo package.json y package-lock.json (si existe) para cachear dependencias
COPY package*.json vite.config.* ./

RUN npm install

# Copiar el resto del proyecto
COPY . .

# Compilar assets con Vite
RUN npm run build

# ===========================
# Stage 2: Backend con PHP 8.3
# ===========================
FROM php:8.3-fpm-bullseye

WORKDIR /var/www/html

# Instalar dependencias del sistema necesarias para extensiones PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
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
    libicu-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    bison \
    re2c \
    flex \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP necesarias
RUN docker-php-ext-install pdo_mysql pdo_pgsql pgsql \
    && docker-php-ext-configure zip --with-zip \
    && docker-php-ext-install zip mbstring bcmath xml ctype curl intl

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar proyecto Laravel (sin node_modules)
COPY . .

# Copiar assets ya compilados desde el stage de Node
COPY --from=build /app/public/build ./public/build

# Instalar dependencias de Laravel en modo producción
RUN php -d memory_limit=-1 /usr/bin/composer install --no-dev --optimize-autoloader --no-interaction

# Ajustar permisos para Laravel
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Exponer puerto
EXPOSE 8000

# Ejecutar Laravel en primer plano
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]