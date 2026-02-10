#!/usr/bin/env bash

echo "Running Composer if needed..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

echo "Caching configs..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Running migrations..."
php artisan migrate --force --no-interaction

echo "Linking storage..."
php artisan storage:link

echo "Starting services..."
# The image's default start (NGINX + PHP-FPM) happens here
exec /start.sh   # or whatever the image uses; often no need if not overriding