FROM php:8.3-fpm
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev zip curl npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer
COPY docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
# Ensure script has Unix line endings and is executable (fixes "not found" / restart loop)
RUN sed -i 's/\r$//' /usr/local/bin/docker-entrypoint.sh && \
    chmod +x /usr/local/bin/docker-entrypoint.sh
WORKDIR /var/www/html
