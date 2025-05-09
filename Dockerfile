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
    npm \
    nginx \
    supervisor

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

# Use Railway environment if it exists
RUN if [ -f .env.railway ]; then cp .env.railway .env; fi

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

# Setup nginx
COPY nginx.conf /etc/nginx/sites-available/default
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

# Setup supervisor
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Default port (will be overridden by environment variable)
ENV PORT=8000

# Expose the port
EXPOSE ${PORT}

# Set up entrypoint
COPY ./docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

CMD ["docker-entrypoint.sh"] 