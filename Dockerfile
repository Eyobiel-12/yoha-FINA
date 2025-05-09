FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install SQLite
RUN apt-get update && apt-get install -y sqlite3 libsqlite3-dev
RUN docker-php-ext-install pdo_sqlite

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Install dependencies
RUN composer install --optimize-autoloader --no-dev

# Create SQLite database
RUN mkdir -p /var/www/database
RUN touch /var/www/database/database.sqlite
RUN chmod -R 777 /var/www/database

# Set permissions
RUN chmod -R 777 /var/www/storage
RUN chmod -R 777 /var/www/bootstrap/cache

# Install npm dependencies and build assets
RUN npm ci && npm run build

# Generate key
RUN php artisan key:generate --force

# Run migrations
RUN php artisan migrate --force

# Create storage link
RUN php artisan storage:link

# Default port (will be overridden by environment variable)
ENV PORT=8000
# Expose the port dynamically
EXPOSE ${PORT}

# Set up entrypoint
COPY ./docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

CMD ["docker-entrypoint.sh"] 