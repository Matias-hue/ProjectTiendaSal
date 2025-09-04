# Imagen base PHP CLI
FROM php:8.2-cli

WORKDIR /var/www/html

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git curl npm \
    && docker-php-ext-install pdo_mysql zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar proyecto
COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Compilar assets de Vite
RUN npm install && npm run build

# Permisos
RUN chmod -R 775 storage bootstrap/cache

# Exponer puerto
EXPOSE 8000

# Arrancar Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
