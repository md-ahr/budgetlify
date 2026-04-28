#!/bin/bash
set -e

echo "=== BUILD STARTED ==="

# -----------------------------------------------
# 1. Install pdo_sqlite PHP extension
# -----------------------------------------------
echo ">>> Installing sqlite3 + pdo_sqlite"
apt-get update -y -qq

# Try PHP version-specific package first, then generic fallback
PHP_VER=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
echo ">>> Detected PHP $PHP_VER"

apt-get install -y -qq sqlite3 libsqlite3-dev "php${PHP_VER}-sqlite3" 2>/dev/null \
  || apt-get install -y -qq sqlite3 libsqlite3-dev php-sqlite3 2>/dev/null \
  || true

# If still missing, compile it manually via pecl/docker-php-ext-install equivalent
if ! php -m | grep -qi pdo_sqlite; then
    echo ">>> Attempting to enable via phpenmod"
    phpenmod pdo_sqlite 2>/dev/null || true
fi

# Verify
if php -m | grep -qi pdo_sqlite; then
    echo ">>> pdo_sqlite: LOADED ✓"
else
    echo ">>> ERROR: pdo_sqlite could not be loaded. Exiting."
    php -m
    exit 1
fi

# -----------------------------------------------
# 2. Setup .env
# -----------------------------------------------
if [ ! -f .env ]; then
    echo ">>> Copying .env.example"
    cp .env.example .env
fi

# -----------------------------------------------
# 3. Composer install
# -----------------------------------------------
echo ">>> Running composer install"
composer install --no-dev --optimize-autoloader --no-interaction

# -----------------------------------------------
# 4. Generate app key
# -----------------------------------------------
php artisan key:generate --force

# -----------------------------------------------
# 5. Create SQLite file
# -----------------------------------------------
echo ">>> Creating SQLite database file"
mkdir -p database
touch database/database.sqlite
chmod 664 database/database.sqlite

# -----------------------------------------------
# 6. Production cache
# -----------------------------------------------
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== BUILD DONE ==="
