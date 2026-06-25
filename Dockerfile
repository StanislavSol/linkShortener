FROM php:8.3-apache

# Устанавливаем ТОЛЬКО необходимые пакеты
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libsqlite3-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql pdo_sqlite zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Включаем mod_rewrite для Laravel
RUN a2enmod rewrite

# Устанавливаем Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Права на папки Laravel
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
RUN chmod -R 775 /app/storage /app/bootstrap/cache

# Установка PHP зависимостей
RUN composer install --no-dev --optimize-autoloader

# Создаем .env если нет
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Настраиваем Apache на папку public
RUN rm -rf /var/www/html && \
    ln -s /app/public /var/www/html

# Настройка Apache
RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf && \
    sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

EXPOSE 80

CMD php artisan key:generate --force && \
    php artisan migrate --force && \
    apache2-foreground
