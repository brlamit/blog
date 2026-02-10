#!/bin/sh
set -e

php artisan migrate --force --no-interaction
php artisan optimize

exec php-fpm  # or exec /start.sh if your image has it