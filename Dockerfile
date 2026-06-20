FROM php:8.3-fpm

# Устанавливаем системные зависимости
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libsqlite3-dev \
    unzip \
    nginx \
    supervisor \
    && docker-php-ext-install pdo pdo_pgsql pdo_sqlite zip \
    && rm -rf /var/lib/apt/lists/*

# Копируем composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Устанавливаем Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    rm -rf /var/lib/apt/lists/*

# Настраиваем рабочую директорию
WORKDIR /app
COPY . .

# Устанавливаем PHP и Node.js зависимости
RUN composer install --no-dev --optimize-autoloader
RUN npm ci && npm run build

# Настраиваем права на storage и cache
RUN mkdir -p storage/logs storage/framework/{sessions,views,cache,testing} storage/app && \
    chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Настраиваем nginx
RUN rm /etc/nginx/sites-enabled/default
COPY nginx.conf /etc/nginx/sites-enabled/default
RUN mkdir -p /var/log/supervisor

# Настраиваем php-fpm
RUN sed -i 's/listen = 127.0.0.1:9000/listen = \/var\/run\/php\/php-fpm.sock/' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's/;listen.mode = 0660/listen.mode = 0666/' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's/listen.owner = www-data/;listen.owner = www-data/' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's/listen.group = www-data/;listen.group = www-data/' /usr/local/etc/php-fpm.d/www.conf

# Копируем конфиг supervisor
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Используем порт Render
EXPOSE 10000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
