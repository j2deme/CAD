#!/bin/bash
set -e

cd /var/www/html

# ------------------------------------------------------------------
# Restaurar artefactos construidos en la imagen cuando el bind mount
# de docker-compose tapa el contenido de /var/www/html
# ------------------------------------------------------------------
if [ ! -f "/var/www/html/public/build/manifest.json" ] && [ -f "/opt/vite-build/manifest.json" ]; then
  echo "Restaurando assets de Vite generados en la imagen..."
  rm -rf /var/www/html/public/build
  cp -r /opt/vite-build /var/www/html/public/build
fi

if [ ! -d "/var/www/html/vendor" ] && [ -d "/opt/app-vendor" ]; then
  echo "Restaurando dependencias PHP (vendor) de la imagen..."
  cp -r /opt/app-vendor /var/www/html/vendor
fi

# Si aun asi no hay vendor, instalar dependencias con Composer
if [ ! -d "/var/www/html/vendor" ]; then
  echo "Instalando dependencias Composer..."
  composer install --no-interaction --prefer-dist
fi

# ------------------------------------------------------------------
# Asegurar que exista .env
# ------------------------------------------------------------------
if [ ! -f "/var/www/html/.env" ]; then
  if [ -f "/var/www/html/.env.example" ]; then
    cp /var/www/html/.env.example /var/www/html/.env
  fi
fi

# ------------------------------------------------------------------
# APP_KEY: generar SOLO si no existe o esta vacia.
# Nunca regenerar en cada arranque (rotar la clave invalida sesiones,
# cookies y datos encriptados).
# ------------------------------------------------------------------
if [ -f "/var/www/html/.env" ]; then
  if ! grep -q '^APP_KEY=base64:' /var/www/html/.env; then
    echo "APP_KEY ausente o vacia — generando una sola vez..."
    php artisan key:generate --force || true
  fi
fi

# ------------------------------------------------------------------
# Enlace simbolico public/storage (usado por Storage::disk('public'))
# ------------------------------------------------------------------
mkdir -p /var/www/html/storage/app/public
php artisan storage:link --force || true

# ------------------------------------------------------------------
# Permisos. Por defecto no se hace chown: en bind mounts de Windows/WSL
# es muy lento y contamina los permisos del host. Activar con FIX_PERMS=true
# ------------------------------------------------------------------
if [ "${FIX_PERMS:-false}" = "true" ]; then
  chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
fi

# Migraciones opcionales al arrancar (AUTO_MIGRATE=true)
if [ "${AUTO_MIGRATE:-false}" = "true" ]; then
  php artisan migrate --force || true
fi

# php-fpm en primer plano (exec para que reciba las senales de la senal)
exec php-fpm -F
