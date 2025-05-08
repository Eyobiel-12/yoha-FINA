#!/bin/bash

# Create database directory and empty SQLite file
mkdir -p database
touch database/database.sqlite

# Copy netlify.env to .env
cp netlify.env .env

# Install composer dependencies without running scripts
composer install --no-scripts --no-interaction

# Install npm dependencies
npm install

# Build front-end assets
npm run build

# Copy index.php to index.html for static fallback
cp public/index.php public/index.html

echo "Build completed without running Laravel database operations" 