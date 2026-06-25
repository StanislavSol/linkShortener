# Меняем на Apache (или оставляем CLI, но добавляем веб-сервер)
FROM php:8.4-apache

# Устанавливаем расширения
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
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Устанавливаем Node.js (LTS, не 24.x)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Копируем файлы проекта
COPY . .

# Установка зависимостей PHP
RUN composer install --no-dev --optimize-autoloader

# Сборка фронтенда
RUN npm ci && npm run build

# СОЗДАЕМ БАЗУ С ПРАВИЛЬНЫМИ ПРАВАМИ
RUN mkdir -p /app/database && \
    touch /app/database/database.sqlite && \
    chown -R www-data:www-data /app/database && \
    chmod -R 775 /app/database

# Права на storage
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache && \
    chmod -R 775 /app/storage /app/bootstrap/cache

# Создаем .env если нет
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Настраиваем Apache на папку public
RUN rm -rf /var/www/html && \
    ln -s /app/public /var/www/html

# Дополнительные настройки Apache
RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf && \
    sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

EXPOSE 80

# Используем migrate (не refresh) чтобы не удалять данные при каждом запуске
CMD php artisan key:generate --force && \
    php artisan migrate --force && \
    apache2-foreground
