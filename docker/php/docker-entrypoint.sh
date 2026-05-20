#!/bin/sh
set -e

echo "Starting docker-entrypoint.sh"

# short wait for database to allow dependent services to start
sleep 5

# set permissive permissions (adjust as needed)
chmod -R 777 storage bootstrap/cache || true

# copy .env if missing
if [ ! -f .env ] && [ -f .env.example ]; then
    cp .env.example .env
fi

# install composer dependencies if vendor missing
if [ ! -d vendor ]; then
    composer install --no-interaction --no-dev --optimize-autoloader || true
fi

# artisan setup (best-effort, don't fail container on DB issues)
php artisan key:generate --force || true
php artisan config:clear || true
php artisan migrate --force || true
php artisan db:seed --class=AdminUserSeeder || true
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Entrypoint finished, launching php-fpm"
exec php-fpm

