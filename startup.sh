#!/bin/bash

# Navigate to site root
cd /home/site/wwwroot

# Install dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader

# Generate key if not set
php artisan key:generate --force

# Run migrations (optional, if you want auto)
php artisan migrate --force

# Start Apache (already included in Azure PHP)
service apache2 restart

# Keep container running
apache2-foreground
