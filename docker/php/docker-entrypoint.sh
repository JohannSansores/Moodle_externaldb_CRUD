#!/bin/sh
set -e

# 1. Copy .env if it doesn't exist
if [ ! -f .env ]; then
    cp .env.example .env
fi

# 2. Install dependencies (optional, if your container doesn't already have them)
composer install --no-dev --optimize-autoloader

# 3. Generate app key if it doesn't exist
php artisan key:generate --force

# 4. Run migrations
php artisan migrate --force

# 5. Seed superadmin
php artisan db:seed --class=AdminUserSeeder

# 6. Cache config/routes/views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# 8. Finally, run the main command (PHP-FPM or artisan serve)
exec php-fpm
