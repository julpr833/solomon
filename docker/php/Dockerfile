FROM php:8.4-fpm

# Instalar las dependencias a nivel sistema
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev

# Compilar y configurar extensiones de PHP necesarias
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www

# Copiar los archivos del repositorio al contenedor
COPY . .

# Instalar dependencias de composer
RUN composer install --no-interaction --optimize-autoloader

# Cambiar permisos para que Laravel tenga acceso
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Puerto estandar de PHP-FPM
EXPOSE 9000

# Comando para iniciar PHP-FPM
CMD ["php-fpm"]