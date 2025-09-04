# 1. Imagen base con PHP 8.2 y FPM
FROM php:8.2-fpm

# 2. Establecer directorio de trabajo
WORKDIR /var/www/html

# 3. Instalar extensiones de PHP necesarias y herramientas
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql zip

# 4. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Copiar todos los archivos del proyecto al contenedor
COPY . .

# 6. Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# 7. Construir assets si usas Vite (opcional)
# RUN npm install && npm run build

# 8. Exponer puerto que usará Render
EXPOSE 8000

# 9. Comando para arrancar Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
