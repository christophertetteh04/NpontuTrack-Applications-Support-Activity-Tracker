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
    chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache && \
    php artisan storage:link || true && \
    chown -R www-data:www-data /var/log/nginx /var/lib/nginx

# Setup MariaDB directories
RUN mkdir -p /var/run/mysqld && chown mysql:mysql /var/run/mysqld

# Prepare Entrypoint
COPY entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80
ENTRYPOINT ["entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
