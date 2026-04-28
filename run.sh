#!/bin/bash
set -e

echo "=== RUN STARTED ==="

# -----------------------------------------------
# 1. Ensure pdo_sqlite is available at runtime too
# -----------------------------------------------
if ! php -m | grep -qi pdo_sqlite; then
    echo ">>> pdo_sqlite missing at runtime, attempting install"
    apt-get install -y -qq sqlite3 php-sqlite3 2>/dev/null || true
    phpenmod pdo_sqlite 2>/dev/null || true
fi

php -m | grep -qi pdo_sqlite && echo ">>> pdo_sqlite: LOADED ✓" || { echo ">>> FATAL: pdo_sqlite not available"; exit 1; }

# -----------------------------------------------
# 2. Recreate SQLite file (ephemeral filesystem)
# -----------------------------------------------
echo ">>> Setting up SQLite database"
mkdir -p /workspace/database
touch /workspace/database/database.sqlite
chmod 664 /workspace/database/database.sqlite

# -----------------------------------------------
# 3. Fix permissions
# -----------------------------------------------
chmod -R 775 storage bootstrap/cache

# -----------------------------------------------
# 4. Run migrations
# -----------------------------------------------
echo ">>> Running migrations"
php artisan migrate --force

# -----------------------------------------------
# 5. Start server
# -----------------------------------------------
echo "=== Starting PHP server on port 8080 ==="
php artisan serve --host=0.0.0.0 --port=8080
