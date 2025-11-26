#!/bin/bash
set -e
echo "Starting deployment..."
cd /home/site/wwwroot
if [ ! -f "composer.phar" ]; then
    echo "Installing Composer..."
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php composer-setup.php --quiet
    php -r "unlink('composer-setup.php');"
    mv composer.phar /usr/local/bin/composer
fi
echo "Installing dependencies..."
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
echo "Setting permissions..."
chmod -R 775 storage bootstrap/cache
mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache
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