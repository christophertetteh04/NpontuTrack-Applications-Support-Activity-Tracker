#!/bin/sh
set -e

# Start MariaDB directly (not via `service`, which requires an init system unavailable in Docker)
mysqld_safe --skip-networking=0 &

# Wait for MariaDB to be ready before proceeding
until mysqladmin ping --silent; do
  echo "Waiting for MariaDB..."
  sleep 2
done

# Initialize Database, User, and run Migrations/Seeding
mysql -e "CREATE DATABASE IF NOT EXISTS npontu; CREATE USER IF NOT EXISTS 'sail'@'localhost' IDENTIFIED BY 'password'; GRANT ALL PRIVILEGES ON npontu.* TO 'sail'@'localhost'; FLUSH PRIVILEGES;"
php artisan migrate --force
(php artisan db:seed --force || echo "Seeding skipped or already exists")

exec "$@"