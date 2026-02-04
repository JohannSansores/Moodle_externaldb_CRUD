FROM php:8.3-fpm
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev zip curl npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer
