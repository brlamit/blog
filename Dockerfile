FROM php:8.2-cli-alpine

# Install system deps + all required extensions (intl + zip fixed previous issues)
RUN apk add --no-cache \
    git curl libpng-dev libjpeg-turbo-dev libwebp-dev \
    postgresql-dev \
    icu-dev \
    libzip-dev \
    zip \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install gd pdo_pgsql exif pcntl bcmath intl zip

# Copy full app code FIRST (artisan must be present for post-install scripts)
COPY . /var/www/html
WORKDIR /var/www/html

# Copy composer from multi-stage
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Now run composer install (artisan exists → package:discover works)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Permissions (run after copy/install)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Expose port for artisan serve
EXPOSE 8000

# Start Laravel built-in server (simple, no NGINX/PHP-FPM needed)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]