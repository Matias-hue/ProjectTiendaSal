# Imagen base PHP CLI
FROM php:8.2-cli

WORKDIR /var/www/html

# Instalar dependencias de sistema y PHP necesarias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    npm \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    pkg-config \
    build-essential \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql zip mbstring bcmath xml tokenizer ctype gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar proyecto
COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Compilar assets de Vite
RUN npm install && npm run build

# Dar permisos correctos
RUN chmod -R 775 storage bootstrap/cache

# Exponer puerto
EXPOSE 8000

# Arrancar Laravel en primer plano
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
