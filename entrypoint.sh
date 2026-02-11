#!/bin/sh
set -e

echo "Starting Laravel entrypoint..."

# Only run composer if vendor folder missing (safety fallback)
if [ ! -d "vendor" ]; then
    echo "Vendor folder missing. Installing dependencies..."
    composer install \
      --no-dev \
      --no-interaction \
      --prefer-dist \
      --optimize-autoloader \
      --no-progress
fi

# Create storage link if needed
echo "Creating storage symlink..."
php artisan storage:link --force || true

# Set permissions
echo "Setting storage and cache permissions..."
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# Laravel caches (optional but good for production)
echo "Caching configuration..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Entrypoint finished. Starting main process..."

exec "$@"
