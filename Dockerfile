FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libsqlite3-dev \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_pgsql pdo_sqlite zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
RUN chmod -R 775 /app/storage /app/bootstrap/cache

# Копируем .env.example в .env если .env не существует
RUN if [ ! -f .env ]; then cp .env.example .env; fi

EXPOSE 10000

CMD php artisan key:generate --force && \
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=10000
