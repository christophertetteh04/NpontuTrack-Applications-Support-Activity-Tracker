FROM php:8.4-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev zip libicu-dev nginx supervisor mariadb-server \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip intl xml dom

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

# Configure Nginx and Supervisor
COPY app.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader && \
    php artisan storage:link && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chown -R www-data:www-data /var/log/nginx /var/lib/nginx

# Setup MariaDB directories
RUN mkdir -p /var/run/mysqld && chown mysql:mysql /var/run/mysqld

EXPOSE 80

CMD service mariadb start && \
    until mysqladmin ping >/dev/null 2>&1; do echo "Waiting for MariaDB..."; sleep 2; done && \
    mysql -e "CREATE DATABASE IF NOT EXISTS npontu; CREATE USER IF NOT EXISTS 'sail'@'localhost' IDENTIFIED BY 'password'; GRANT ALL PRIVILEGES ON npontu.* TO 'sail'@'localhost'; FLUSH PRIVILEGES;" && \
    php artisan migrate --force --seed && \
    /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
