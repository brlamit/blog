# -----------------------------
# Stage 1: Install PHP & Composer dependencies
# -----------------------------
FROM php:8.2-fpm-alpine AS base

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

# Copy composer files
COPY composer.json composer.lock ./

# Install composer (from official composer image)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP dependencies WITHOUT running scripts (artisan not copied yet)
RUN composer install --no-dev --optimize-autoloader --no-scripts --prefer-dist

# -----------------------------
# Stage 2: Copy application code + run artisan commands
# -----------------------------
COPY . .

# Now artisan exists → run scripts safely
RUN php artisan package:discover --ansi \
    && php artisan storage:link --force \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# -----------------------------
# Stage 3: Build frontend assets (Vite + Tailwind)
# -----------------------------
RUN npm ci && npm run build

# -----------------------------
# Stage 4: Final image ready to serve
# -----------------------------
EXPOSE 8000

# Entrypoint: run PHP-FPM (production)
CMD ["php-fpm"]
