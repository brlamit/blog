FROM php:8.2-cli-alpine

# Install PHP extensions + Node.js/npm for Vite
RUN apk add --no-cache \
    git curl libpng-dev libjpeg-turbo-dev libwebp-dev \
    postgresql-dev \
    icu-dev \
    libzip-dev \
    zip \
    nodejs npm \                          
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install gd pdo_pgsql exif pcntl bcmath intl zip

# Copy full app code (artisan must be present)
COPY . /var/www/html
WORKDIR /var/www/html

# Composer deps
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Frontend build (this generates /public/build/ with manifest.json)
RUN npm ci && npm run build

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Run artisan serve on port 8000
EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]