FROM php:8.3-fpm

# Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libsqlite3-dev \
    unzip \
    nginx \
    supervisor \
    && docker-php-ext-install pdo pdo_pgsql pdo_sqlite zip \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Node.js (стабильная версия)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

WORKDIR /app
COPY . .

# PHP-зависимости (без dev)
RUN composer install --no-dev --optimize-autoloader

# Фронтенд
RUN npm ci && npm run build

# Права на storage
RUN mkdir -p storage/logs storage/framework storage/app && \
    chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Кеширование Laravel
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Nginx конфиг
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 10000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
