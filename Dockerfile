FROM richarvey/nginx-php-fpm:3.1.6

# Install only the dependencies you actually need
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    postgresql-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install gd pdo_pgsql exif pcntl bcmath

# Copy your Laravel app
COPY . /var/www/html

# Key env vars for the image to behave correctly
ENV SKIP_COMPOSER=1
ENV WEBROOT=/var/www/html/public          
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1

# Fix permissions (Laravel storage must be writable by www-data)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# No custom CMD/ENTRYPOINT needed — image starts NGINX + PHP-FPM automatically

EXPOSE 80