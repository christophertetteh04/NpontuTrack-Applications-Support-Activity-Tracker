#!/bin/sh
set -e

# Ensure storage directory structure exists for the mounted volume
mkdir -p /var/www/html/storage/app/public/shift-seals
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

# Start MariaDB directly (not via `service`, which requires an init system unavailable in Docker)
mysqld_safe --skip-networking=0 &

# Wait for MariaDB to be ready before proceeding
until mysqladmin ping --silent; do
  echo "Waiting for MariaDB..."
  sleep 2
done

# Initialize Database, User, and run Migrations/Seeding
mysql -e "CREATE DATABASE IF NOT EXISTS \`${DB_DATABASE:-npontu}\`; CREATE USER IF NOT EXISTS '${DB_USERNAME:-sail}'@'%' IDENTIFIED BY '${DB_PASSWORD:-password}'; GRANT ALL PRIVILEGES ON \`${DB_DATABASE:-npontu}\`.* TO '${DB_USERNAME:-sail}'@'%'; FLUSH PRIVILEGES;"
php artisan migrate --force
(php artisan db:seed --force || echo "Seeding skipped or already exists")

exec "$@"