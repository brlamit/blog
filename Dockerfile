# Use the community-maintained, Render-recommended image with NGINX + PHP-FPM
FROM richarvey/nginx-php-fpm:3.1.6

# Set Laravel-optimized environment variables (these are read by the image's start script)
                # For proper X-Forwarded-For handling on Render

# Copy application code
COPY . /var/www/html

# Install dependencies (the image has composer; this runs if needed)
# But since we copy composer files first in practice, caching works
# RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# The image already has most extensions; add any extras if missing (e.g., gd, pgsql)
# Most are pre-installed in recent tags, but to be safe:
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    postgresql-dev \
    tesseract-ocr \
    tesseract-ocr-data-eng \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install gd pdo_pgsql exif pcntl bcmath

# Permissions for Laravel (storage & cache must be writable)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# The image's built-in start.sh handles:
# - Composer install if needed
# - artisan storage:link if missing
# - Starts supervisord → NGINX + PHP-FPM
# No custom ENTRYPOINT or CMD needed!

# Expose port 80 (NGINX listens here; Render proxies automatically)
EXPOSE 80