# === PRODUCTION DOCKERFILE ===
FROM php:8.2-fpm-alpine

# Install system dependencies + PHP extensions
RUN apk add --no-cache \
    icu-dev \
    libzip-dev \
    libpng-dev \
    oniguruma-dev \
    bash \
    nginx \
    postgresql-dev \
    zip \
    unzip \
    git \
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

# Set workdir
WORKDIR /var/www/html

# Copy app + install composer
COPY composer.json composer.lock ./
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Copy rest of the app
COPY . .

# Build frontend assets
RUN npm install && npm run build

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Copy Nginx config
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Expose port
EXPOSE 80

# Start PHP-FPM + Nginx
CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]
