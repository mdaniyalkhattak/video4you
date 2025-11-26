#!/bin/bash
set -e
cd /home/site/wwwroot
echo "Installing Composer..."
if [ ! -f "composer.phar" ]; then
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php composer-setup.php --quiet
    php -r "unlink('composer-setup.php');"
fi
echo "Installing dependencies..."
php composer.phar install --no-dev --prefer-dist --optimize-autoloader --no-interaction
echo "Setting permissions..."
chmod -R 775 storage bootstrap/cache
mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs bootstrap/cache
echo "Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo "Caching config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "Running migrations..."
php artisan migrate --force --no-interaction
echo "Deployment complete!"