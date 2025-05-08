# Usa una imagen base de PHP con Apache
FROM php:8.1-apache

# Instala extensiones necesarias para Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Habilita el módulo de reescritura de Apache
RUN a2enmod rewrite

# Instala Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos del proyecto al contenedor
COPY . /var/www/html

# Cambia los permisos del directorio de almacenamiento y caché
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Instala dependencias de Composer y npm
RUN composer install --no-dev --optimize-autoloader
RUN npm install

# Genera la clave de la aplicación
RUN php artisan key:generate

# Ejecuta las migraciones y seeders
RUN php artisan migrate --force
RUN php artisan db:seed --force

# Compila los assets
RUN npm run dev

# Crea el enlace simbólico para storage
RUN php artisan storage:link

# Configura el archivo de host virtual de Apache
COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Expone el puerto 80
EXPOSE 80

# Comando por defecto para iniciar Apache
CMD ["apache2-foreground"]