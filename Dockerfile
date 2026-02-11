FROM php:8.2-cli-alpine

# Install system deps + extensions
RUN apk add --no-cache \
    git curl libpng-dev libjpeg-turbo-dev libwebp-dev \
    postgresql-dev \
    icu-dev \
    libzip-dev \
    zip \
    nodejs npm \   
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install gd pdo_pgsql exif pcntl bcmath intl zip

# Copy full app early (for artisan in composer scripts)
COPY . /var/www/html
WORKDIR /var/www/html

# Composer multi-stage
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Install frontend deps + build Vite assets (critical!)
RUN npm install && npm run build

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Expose port for artisan serve
EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]