#!/bin/bash
set -e

# Set correct permissions again just to be sure
chmod -R 777 /var/www/storage
chmod -R 777 /var/www/bootstrap/cache
chmod -R 777 /var/www/database

# Make sure the SQLite database exists
if [ ! -f "/var/www/database/database.sqlite" ]; then
    touch /var/www/database/database.sqlite
    chmod 777 /var/www/database/database.sqlite
fi

# Install/update dependencies if needed
composer install --optimize-autoloader --no-dev
php artisan optimize:clear

# Create storage link if it doesn't exist
php artisan storage:link --force

# Run migrations if needed
php artisan migrate --force

echo "Laravel application starting on port 8000..."
php artisan serve --host=0.0.0.0 --port=8000 