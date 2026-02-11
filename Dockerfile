############################################
# STAGE 1 — Composer
############################################
FROM composer:latest AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader


############################################
# STAGE 2 — Node build (Vite)
############################################
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build


############################################
# STAGE 3 — PHP-FPM
############################################
FROM php:8.2-fpm-alpine

# Install dependencies + PHP extensions
RUN apk add --no-cache \
    icu-dev \
    libzip-dev \
    libpng-dev \
    oniguruma-dev \
    postgresql-dev \
    bash \
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

# Copy app
COPY . .

# Copy vendor from composer stage
COPY --from=vendor /app/vendor ./vendor

# Copy frontend assets
COPY --from=frontend /app/public/build ./public/build

RUN chown -R www-data:www-data storage bootstrap/cache

CMD ["php-fpm"]
