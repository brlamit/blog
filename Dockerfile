# -----------------------------
# Stage 1: Build frontend assets
# -----------------------------
FROM node:18-alpine AS node-builder

WORKDIR /build

# Copy only package files and configs
COPY package*.json tailwind.config.js vite.config.js ./

# Copy resources folder
COPY resources ./resources

# Install node modules and build assets
RUN npm ci
RUN npm run build

# -----------------------------
# Stage 2: PHP Runtime
# -----------------------------
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
    pkgconf \
    bash \
    shadow \
    tini

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    zip

# Install latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies without running artisan scripts yet
RUN composer install --no-dev --optimize-autoloader --no-scripts --prefer-dist

# Copy full Laravel project
COPY . .

# Use production env by default
RUN if [ -f .env.production ]; then cp .env.production .env; else cp .env.example .env || true; fi

# Create storage symlink for images
RUN php artisan storage:link || true

# Copy built frontend assets
COPY --from=node-builder /build/public/build ./public/build

# Set permissions for Laravel storage and cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy entrypoint script if you have one
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose port (Render will use $PORT)
EXPOSE 8000

# Use tini as init to handle signals properly
ENTRYPOINT ["tini", "--", "/usr/local/bin/entrypoint.sh"]

# -----------------------------
# Run Laravel with proper setup
# -----------------------------
CMD sh -c '\
    if ! grep -q "APP_KEY=" .env; then php artisan key:generate --force; fi && \
    php artisan config:clear && \
    php artisan cache:clear && \
    php artisan route:clear && \
    php artisan view:clear && \
    php artisan config:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    php artisan storage:link || true && \
    php -S 0.0.0.0:$PORT -t public \
'
