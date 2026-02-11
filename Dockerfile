FROM php:8.2-fpm-alpine

# Install dependencies
RUN apk add --no-cache \
    git curl libpng-dev libjpeg-turbo-dev libwebp-dev postgresql-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install gd pdo_pgsql exif pcntl bcmath

# Copy app
COPY . /var/www/html
WORKDIR /var/www/html

# Install composer deps
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 8000 (artisan serve default)
EXPOSE 8000

# Start Laravel built-in server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]