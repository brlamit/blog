#!/bin/sh
set -e

# Run migrations (keep this)
php artisan migrate --force --no-interaction || echo "Migrations skipped"

# Optimize
php artisan optimize || true

# Start PHP-FPM in background
php-fpm -D

# Start NGINX in foreground
exec nginx -g "daemon off;"