FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libsqlite3-dev \
    unzip \
    nginx \
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
    chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

RUN rm -rf /etc/nginx/sites-enabled/default
COPY nginx.conf /etc/nginx/sites-enabled/default

EXPOSE 10000

CMD ["sh", "-c", "nginx -g 'daemon off;' & php artisan serve --host=0.0.0.0 --port=10000"]
