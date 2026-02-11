############################################
# STAGE 1 — Composer (FIXED)
############################################
FROM php:8.2-cli-alpine AS vendor

# Install dependencies needed for composer + filament
RUN apk add --no-cache \
    icu-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install intl zip

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader


############################################
# STAGE 2 — Node build (Vite assets)
############################################
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package*.json ./

RUN npm install

COPY . .

RUN npm run build


############################################
# STAGE 3 — Final PHP-FPM image
############################################
FROM php:8.2-fpm-alpine

# Install PHP extensions required by Laravel + Filament
RUN apk add --no-cache \
    icu-dev \
    libzip-dev \
    libpng-dev \
    oniguruma-dev \
    postgresql-dev \
    bash \
    nginx \
    && docker-php-ext-install \
        pdo_mysql \
        pdo_pgsql \
        mbstring \
        gd \
        intl \
        zip \
        bcmath \
        exif \
        pcntl

WORKDIR /var/www/html

# Copy application code
COPY . .

# Copy vendor from composer stage
COPY --from=vendor /app/vendor ./vendor

# Copy frontend assets
COPY --from=frontend /app/public/build ./public/build

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache

CMD ["php-fpm"]
