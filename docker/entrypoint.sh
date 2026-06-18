#!/usr/bin/env bash
set -e

cd /var/www/html

if [ ! -f .env ] && [ -f docker/.env.docker ]; then
    cp docker/.env.docker .env
fi

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache public/uploads/catalog public/uploads/url
chown -R www-data:www-data storage bootstrap/cache public/uploads || true

if [ ! -f vendor/autoload.php ]; then
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

php artisan package:discover --ansi || true
php artisan storage:link || true

exec "$@"
