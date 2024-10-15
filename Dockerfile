FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    zip \
    git \
    curl \
    libzip-dev \
    && docker-php-ext-install pdo_mysql zip

COPY --from=composer:2.1 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

RUN composer install

RUN cp .env.example .env \
    && php artisan key:generate
