FROM richarvey/nginx-php-fpm:3.1.6

# Copy custom NGINX config first
COPY conf/nginx/default.conf /etc/nginx/conf.d/default.conf

# Install system/PHP dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    postgresql-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install gd pdo_pgsql exif pcntl bcmath

# Copy the ENTIRE app early so artisan is present for composer's post-scripts
COPY . /var/www/html

# Now run composer install (vendor will be created here, artisan is available)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Env vars for runtime
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1

# Fix permissions (run after copy/install)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80