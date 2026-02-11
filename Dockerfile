FROM php:8.2-fpm-alpine

# Install system dependencies + required extensions for your packages
RUN apk add --no-cache \
    git curl libpng-dev libjpeg-turbo-dev libwebp-dev \
    postgresql-dev \
    icu-dev \
    libzip-dev \
    zip \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install gd pdo_pgsql exif pcntl bcmath intl zip

# Copy composer from composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first (for caching)
COPY composer.json composer.lock ./

# Install dependencies (now should succeed)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copy the rest of the app
COPY . .

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Expose port 8000 (for artisan serve)
EXPOSE 8000

# Start Laravel built-in server (no PHP-FPM/NGINX needed)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]