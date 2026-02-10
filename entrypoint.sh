#!/bin/sh
set -e

# Run one-time tasks
php artisan migrate --force --no-interaction || echo "Migration skipped"
php artisan optimize || true
php artisan storage:link || true

# Start PHP-FPM in background
php-fpm -D

# Start NGINX in foreground (daemon off is important!)
exec nginx -g "daemon off;"