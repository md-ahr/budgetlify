#!/bin/bash
set -e

echo "=== RUN STARTED ==="

# Recreate SQLite database (ephemeral filesystem resets on every deploy)
mkdir -p database
touch database/database.sqlite
chmod 664 database/database.sqlite

# Fix storage permissions
chmod -R 775 storage bootstrap/cache

# Run migrations
php artisan migrate --force

echo "=== Starting PHP server on port 8080 ==="
php artisan serve --host=0.0.0.0 --port=8080
