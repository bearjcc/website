#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html

# Production sanity defaults if not provided
: "${APP_ENV:=production}"
: "${APP_DEBUG:=false}"

# Ensure APP_KEY
if [ -z "${APP_KEY:-}" ] || [ "$APP_KEY" = "" ]; then
  echo "APP_KEY is missing; generating one..."
  php artisan key:generate --force
fi

# Storage link (idempotent - check first to avoid error message)
if [ ! -L "/var/www/html/public/storage" ]; then
  php artisan storage:link
fi

# Cache config, routes, views
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optional: run migrations if you want auto-apply
if [ "${RUN_MIGRATIONS:-0}" = "1" ]; then
  php artisan migrate --force
fi

# Write logs to stderr for Railway visibility
php -r "file_put_contents('php://stderr','App booted' . PHP_EOL);"

# Hand off to supervisord (nginx + php-fpm)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

