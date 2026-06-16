#!/bin/sh
set -e
cd /var/www/html
if [ ! -f database/database.sqlite ]; then
  touch database/database.sqlite
fi
php artisan migrate --force
php artisan db:seed --force 2>/dev/null || true
exec php artisan serve --host=0.0.0.0 --port=8000
