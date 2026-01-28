# ---------- Build Stage: Node Assets ----------
FROM node:20-bullseye AS nodebuild
WORKDIR /app

# Copy only asset sources first for better caching
COPY package*.json vite.config.* postcss.config.* tailwind.config.* ./
RUN npm ci

# Bring in the rest to build assets
COPY resources ./resources
COPY public ./public
RUN npm run build

# ---------- Build Stage: Composer Dependencies ----------
FROM composer:2 AS composerbuild
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-scripts --prefer-dist --optimize-autoloader --ignore-platform-reqs

# ---------- Runtime Stage: PHP-FPM + Nginx ----------
FROM php:8.3-fpm-bullseye AS app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx supervisor curl git unzip \
    libonig-dev libzip-dev libpng-dev libxml2-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql opcache zip \
    && rm -rf /var/lib/apt/lists/*

# PHP production config
RUN { \
  echo "log_errors=On"; \
  echo "error_reporting=E_ALL"; \
  echo "display_errors=Off"; \
  echo "memory_limit=512M"; \
  echo "opcache.enable=1"; \
  echo "opcache.validate_timestamps=0"; \
  echo "opcache.memory_consumption=128"; \
  echo "opcache.interned_strings_buffer=8"; \
  echo "opcache.max_accelerated_files=4000"; \
} > /usr/local/etc/php/conf.d/prod.ini

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Bring in vendor from composer stage
COPY --from=composerbuild /app/vendor /var/www/html/vendor

# Bring in built assets from node stage (Vite creates public/build)
COPY --from=nodebuild /app/public/build /var/www/html/public/build

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R ug+rwx storage bootstrap/cache

# Nginx config
RUN rm -f /etc/nginx/sites-enabled/default
COPY deploy/nginx.conf /etc/nginx/conf.d/default.conf

# Supervisor config (to run php-fpm + nginx)
COPY deploy/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Startup script: prep Laravel, then start supervisord
COPY deploy/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8080

CMD ["/entrypoint.sh"]
