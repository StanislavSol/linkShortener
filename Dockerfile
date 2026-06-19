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

RUN mkdir -p storage/logs storage/framework storage/app && \
    mkdir -p /var/log/supervisor && \
    chown -R www-data:www-data storage bootstrap/cache /var/log/supervisor && \
    chmod -R 775 storage bootstrap/cache /var/log/supervisor

RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

RUN rm -rf /etc/nginx/sites-enabled/default
COPY docker/nginx.conf /etc/nginx/sites-available/link-shortener
RUN ln -s /etc/nginx/sites-available/link-shortener /etc/nginx/sites-enabled/link-shortener

COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 10000

CMD ["sh", "-c", "nginx -g 'daemon off;' & php-fpm8.3 -F"]
