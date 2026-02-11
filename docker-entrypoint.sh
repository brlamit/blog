#!/bin/sh
set -e

echo "🚀 Starting Laravel entrypoint..."

# Ensure storage symlink exists
php artisan storage:link --force || true

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache configs/routes/views for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "🏁 Laravel entrypoint finished. Starting server..."

# Execute the main process
exec "$@"
