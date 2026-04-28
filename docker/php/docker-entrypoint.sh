#!/bin/sh

echo "Starting entrypoint script"

# Wait for database
echo "Sleeping 15"
sleep 15
echo "Sleep done"

# Set permissions
echo "Setting permissions"
chmod -R 777 storage bootstrap/cache
echo "Permissions set"

# 1. Copy .env if it doesn't exist
if [ ! -f .env ]; then
    echo "Copying .env"
    cp .env.example .env
fi

# 2. Install dependencies (optional, if your container doesn't already have them)
echo "Installing composer"
composer install --no-dev --optimize-autoloader

# 3. Generate app key if it doesn't exist
echo "Generating key"
php artisan key:generate --force

# 4. Clear config cache
echo "Clearing config"
php artisan config:clear

# 5. Run migrations
echo "Migrating"
php artisan migrate --force

# 6. Seed superadmin
echo "Seeding"
php artisan db:seed --class=AdminUserSeeder || echo "Seeding failed, continuing..."

# 7. Cache config/routes/views
echo "Caching config"
php artisan config:cache
echo "Caching routes"
php artisan route:cache
echo "Caching views"
php artisan view:cache

echo "Done"
exec php-fpm
