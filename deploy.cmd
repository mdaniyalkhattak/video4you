@echo off
echo Starting deployment...

:: Install Composer dependencies
echo Installing Composer dependencies...
call composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

:: Set permissions (if on Linux container)
echo Setting permissions...
if exist /home/site/wwwroot (
    chmod -R 775 storage bootstrap/cache 2>nul
)

:: Create necessary directories
echo Creating directories...
if not exist "storage\framework\sessions" mkdir storage\framework\sessions
if not exist "storage\framework\views" mkdir storage\framework\views
if not exist "storage\framework\cache" mkdir storage\framework\cache
if not exist "storage\logs" mkdir storage\logs
if not exist "bootstrap\cache" mkdir bootstrap\cache

:: Clear caches
echo Clearing caches...
call php artisan config:clear
call php artisan cache:clear
call php artisan route:clear
call php artisan view:clear

:: Cache configuration
echo Caching configuration...
call php artisan config:cache
call php artisan route:cache  
call php artisan view:cache

:: Run migrations
echo Running migrations...
call php artisan migrate --force --no-interaction

echo Deployment completed!