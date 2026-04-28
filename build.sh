#!/bin/bash
set -e

echo "=== BUILD STARTED ==="

# Install PHP dependencies
composer install --no-dev --optimize-autoloader --no-interaction

# Copy .env
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generate app key
php artisan key:generate --force

# Create SQLite database file
mkdir -p database
touch database/database.sqlite

# Cache config/routes/views for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== BUILD DONE ==="
