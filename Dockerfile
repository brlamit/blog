# Base image
FROM php:8.2-fpm-alpine

# Install system dependencies + PHP extensions
RUN apk add --no-cache \
    bash \
    git \
    icu-dev \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    oniguruma-dev \
    postgresql-dev \
    nodejs npm \
    mysql-client \
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

# Set working directory
WORKDIR /var/www/html

# Copy composer files and install composer
COPY composer.json composer.lock ./
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install dependencies without running artisan scripts yet
RUN composer install --no-dev --optimize-autoloader --no-scripts --prefer-dist

# Copy full application
COPY . .

# Run artisan post-install commands safely
RUN php artisan package:discover --ansi \
    && php artisan storage:link --force \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Build frontend assets (Vite + Tailwind)
RUN npm ci && npm run build

# Expose Laravel default port
EXPOSE 8000

# Entrypoint to always run artisan serve
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
