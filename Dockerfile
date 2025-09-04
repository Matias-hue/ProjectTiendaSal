# Imagen base PHP-FPM
FROM php:8.2-fpm-bullseye

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
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-configure zip --with-zip && docker-php-ext-install zip
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install xml
RUN docker-php-ext-configure tokenizer && docker-php-ext-install tokenizer
RUN docker-php-ext-install ctype
RUN docker-php-ext-install curl
RUN docker-php-ext-install openssl
RUN docker-php-ext-install intl

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar proyecto
COPY . .

# Verificar archivos copiados (para depuración)
RUN ls -la

# Instalar dependencias de Laravel
RUN php -d memory_limit=-1 /usr/bin/composer install --no-dev --optimize-autoloader --no-interaction

# Compilar assets de Vite
RUN npm install && npm run build

# Dar permisos
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Exponer puerto
EXPOSE 8000

# Ejecutar Laravel en primer plano
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]