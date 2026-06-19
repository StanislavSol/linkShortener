FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libsqlite3-dev \
    unzip \
    nginx \
    supervisor \
    && docker-php-ext-install pdo pdo_pgsql pdo_sqlite zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm ci && npm run build

RUN mkdir -p storage/logs storage/framework storage/app /var/log/supervisor && \
    chown -R www-data:www-data storage bootstrap/cache /var/log/supervisor && \
    chmod -R 775 storage bootstrap/cache /var/log/supervisor

RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Создаем конфиг для PHP-FPM
RUN echo "listen = 127.0.0.1:9000" > /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "listen.owner = www-data" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "listen.group = www-data" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "listen.mode = 0660" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "user = www-data" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "group = www-data" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "pm = dynamic" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "pm.max_children = 5" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "pm.start_servers = 2" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "pm.min_spare_servers = 1" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "pm.max_spare_servers = 3" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "clear_env = no" >> /usr/local/etc/php-fpm.d/zz-docker.conf

RUN rm -rf /etc/nginx/sites-enabled/default
COPY nginx.conf /etc/nginx/sites-available/laravel
RUN ln -s /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/laravel

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 10000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
