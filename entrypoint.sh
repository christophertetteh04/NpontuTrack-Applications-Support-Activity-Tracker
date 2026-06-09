#!/bin/sh
set -e

# Start MariaDB in the background
service mariadb start

# Wait for MariaDB to be ready before proceeding
until mysqladmin ping >/dev/null 2>&1; do
  echo "Waiting for MariaDB..."
  sleep 2
done

# Initialize Database, User, and run Migrations/Seeding
mysql -e "CREATE DATABASE IF NOT EXISTS npontu; CREATE USER IF NOT EXISTS 'sail'@'localhost' IDENTIFIED BY 'password'; GRANT ALL PRIVILEGES ON npontu.* TO 'sail'@'localhost'; FLUSH PRIVILEGES;"
php artisan migrate --force
(php artisan db:seed --force || echo "Seeding skipped or already exists")

exec "$@"