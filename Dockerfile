# Stage 1: Build assets with Node
FROM node:18-alpine AS node-builder

WORKDIR /build

COPY package*.json tailwind.config.js vite.config.js ./
COPY resources ./resources

RUN npm ci && npm run build


# Stage 2: PHP runtime
FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    mysql-client \
    postgresql-dev \
    bash \
    libzip-dev \
    pkgconf

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd intl zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Copy project
COPY . .

# Copy built assets
COPY --from=node-builder /build/public/build ./public/build

# Use production env
RUN if [ -f .env.production ]; then cp .env.production .env; else cp .env.example .env || true; fi

# ✅ CREATE STORAGE LINK (VERY IMPORTANT FOR IMAGES)
RUN php artisan storage:link || true

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Expose port
EXPOSE 8000

# Start Laravel
CMD sh -c '\
    if ! grep -q "APP_KEY=" .env; then php artisan key:generate --force; fi && \
    php artisan config:clear && \
    php artisan cache:clear && \
    php artisan view:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan migrate --force && \
    php artisan storage:link || true && \
    php artisan serve --host=0.0.0.0 --port=$PORT \
'
