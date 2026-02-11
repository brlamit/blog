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

# Copy composer files and install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --prefer-dist

# Copy full application code
COPY . .

# Copy production env if exists
COPY .env.production* ./

# Copy built assets from Node builder
COPY --from=node-builder /build/public/build ./public/build

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port
EXPOSE 8000

# Run Laravel migrations and start the server
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000
