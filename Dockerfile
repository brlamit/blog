FROM php:8.2-fpm-alpine

# Install system dependencies + Node.js/npm for Vite
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    mysql-client \
    postgresql-dev \
    bash \
    nodejs npm \                      
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files and install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copy application code
COPY . .

# Install frontend deps + build Vite assets (this creates /public/build/manifest.json)
RUN npm ci && npm run build

# Copy docker entrypoint (keep your existing one if needed)
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Expose port
EXPOSE 8000

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

# Start Laravel built-in server (same as your working project)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]