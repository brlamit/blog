FROM richarvey/nginx-php-fpm:3.1.6

# Copy custom NGINX site config
COPY conf/nginx/default.conf /etc/nginx/conf.d/default.conf

# Install dependencies you need
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    postgresql-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install gd pdo_pgsql exif pcntl bcmath

# Copy composer files first (for layer caching)
COPY composer.json composer.lock ./

# Install Composer dependencies during build (production mode)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copy the rest of the app (after composer, so vendor is already there)
COPY . /var/www/html

# Env vars (remove or change SKIP_COMPOSER since we install at build)
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80