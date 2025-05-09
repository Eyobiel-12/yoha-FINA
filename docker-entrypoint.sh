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

# Update Nginx config with correct PORT
PORT=${PORT:-8000}
sed -i "s/\${PORT}/$PORT/" /etc/nginx/sites-available/default

# Install/update dependencies if needed
composer install --optimize-autoloader --no-dev

# Create storage link if it doesn't exist
php artisan storage:link --force

# Run migrations if needed
php artisan migrate --force

# Clear caches
php artisan optimize:clear

echo "Starting Laravel application on port $PORT with Nginx and PHP-FPM..."
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf 