#!/bin/sh
set -e

echo "Starting Laravel entrypoint..."

# Always run composer install on startup (safe & ensures consistency)
echo "Installing/updating composer dependencies..."
composer install \
  --no-dev \
  --no-interaction \
  --prefer-dist \
  --optimize-autoloader \
  --no-progress \
  --no-suggest

# Create storage link if needed
echo "Creating storage symlink..."
php artisan storage:link --force || true

# Set permissions (storage needs 775, bootstrap/cache too)
echo "Setting storage and cache permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache || true

# Optional: cache configs/routes/views (faster production)
echo "Caching configuration..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Entrypoint finished. Starting main process..."

# Hand over to the original command (php-fpm, artisan serve, etc.)
exec "$@"