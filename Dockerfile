FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libsqlite3-dev \
    unzip \
    nodejs \
    npm \
    nginx \
    && docker-php-ext-install pdo pdo_pgsql pdo_sqlite zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
RUN chmod -R 775 /app/storage /app/bootstrap/cache

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Копируем конфиг Nginx
COPY nginx.conf /etc/nginx/sites-available/default

EXPOSE 80

CMD php artisan key:generate --force && \
    php artisan migrate --force && \
    php-fpm -D && \
    nginx -g 'daemon off;'
