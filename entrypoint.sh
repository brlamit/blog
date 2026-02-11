#!/bin/sh
set -e

echo "🚀 Starting Laravel entrypoint..."

# 1️⃣ Ensure storage symlink exists
echo "🔗 Creating storage symlink..."
php artisan storage:link --force || true

# 2️⃣ Set permissions for storage and cache
echo "🛠 Setting permissions..."
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# 3️⃣ Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 4️⃣ Cache configs/routes/views for faster production
echo "⚡ Caching configs, routes, views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5️⃣ Start the main process (php-fpm)
echo "🏁 Starting PHP-FPM..."
exec "$@"
