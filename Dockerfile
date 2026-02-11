# -------------------------
# 1️⃣ Base image
# -------------------------
FROM php:8.2-fpm-alpine

# -------------------------
# 2️⃣ Install system dependencies + PHP extensions
# -------------------------
RUN apk add --no-cache \
    bash \
    git \
    icu-dev \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    oniguruma-dev \
    postgresql-dev \
    nodejs npm \
    mysql-client \
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

# -------------------------
# 3️⃣ Set working directory
# -------------------------
WORKDIR /var/www/html

# -------------------------
# 4️⃣ Copy composer files and install dependencies without running artisan scripts
# -------------------------
COPY composer.json composer.lock ./
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP dependencies (no scripts yet)
RUN composer install --no-dev --optimize-autoloader --no-scripts --prefer-dist

# -------------------------
# 5️⃣ Copy full application
# -------------------------
COPY . .

# -------------------------
# 6️⃣ Build frontend assets (Vite + Tailwind)
# -------------------------
RUN npm ci && npm run build

# -------------------------
# 7️⃣ Set entrypoint
# -------------------------
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

# -------------------------
# 8️⃣ Expose port and default CMD
# -------------------------
EXPOSE 8000
CMD ["php-fpm"]
