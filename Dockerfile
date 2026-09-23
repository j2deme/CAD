FROM php:8.2-fpm

WORKDIR /var/www/html

# Instalar dependencias del sistema y Node.js
RUN apt-get update \
  && apt-get install -y git unzip zip libzip-dev libpng-dev libonig-dev libxml2-dev libpq-dev libicu-dev libfreetype6-dev libjpeg62-turbo-dev ca-certificates curl gnupg2 \
  && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
  && apt-get install -y nodejs \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy package files and install node dependencies (for building assets)
COPY package.json package-lock.json* /var/www/html/
RUN npm ci --silent || npm install --legacy-peer-deps --silent

# Copy composer files first to leverage Docker cache
COPY composer.json composer.lock* /var/www/html/

# Install PHP dependencies (no autoload/scripts yet to speed up build)
RUN composer install --no-dev --prefer-dist --no-interaction --no-scripts --no-autoloader

# Copy application files
COPY . /var/www/html

# Build Vite assets
RUN npm run build

# Generate optimized autoloader and run any post-install scripts
RUN composer dump-autoload --optimize \
  && php artisan package:discover --ansi

# Guardar copias de los artefactos fuera de /var/www/html: docker-compose monta
# el proyecto sobre /var/www/html y los oculta; el entrypoint los restaura.
RUN cp -r /var/www/html/public/build /opt/vite-build \
  && cp -r /var/www/html/vendor /opt/app-vendor

# Entrypoint script (will ensure vendor, permissions, optional migrations at container start)
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true

EXPOSE 9000

CMD ["docker-entrypoint.sh"]
