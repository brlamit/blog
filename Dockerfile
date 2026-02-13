# ==================================================
# Stage 1 — Build frontend assets (Vite / Tailwind)
# ==================================================
FROM node:18-alpine AS node-builder

WORKDIR /app

# Copy only required files first (better cache)
COPY package*.json vite.config.js tailwind.config.js ./
RUN npm ci

# Copy frontend resources
COPY resources ./resources

# Build assets
RUN npm run build


# ==================================================
# Stage 2 — Composer dependencies
# ==================================================
FROM composer:latest AS composer-builder

WORKDIR /app

COPY composer.json composer.lock ./

# Install only production dependencies
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist


# ==================================================
# Stage 3 — Production PHP Runtime
# ==================================================
FROM php:8.2-fpm-alpine

# Install system packages
RUN apk add --no-cache \
    bash \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    oniguruma-dev \
    libzip-dev \
    postgresql-dev \
    mysql-client \
    zip \
    unzip \
    tini

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    bcmath \
    gd \
    intl \
    zip \
    exif \
    pcntl

# Enable OPcache (production performance)
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini

# Set working directory
WORKDIR /var/www/html

# Copy Laravel project
COPY . .

# Copy vendor from composer stage (FASTER builds)
COPY --from=composer-builder /app/vendor ./vendor

# Copy built frontend assets
COPY --from=node-builder /app/public/build ./public/build

# Create env if not exists
RUN if [ -f .env.production ]; then cp .env.production .env; else cp .env.example .env || true; fi

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Use tini for proper signal handling
ENTRYPOINT ["tini","--"]

# Expose Render port
EXPOSE 8000

# ==================================================
# Production startup
# ==================================================
CMD sh -c '\
php artisan config:clear && \
php artisan cache:clear && \
php artisan config:cache && \
php artisan view:cache && \
php artisan migrate --force || true && \
php artisan storage:link || true && \
php -S 0.0.0.0:$PORT -t public \
'
