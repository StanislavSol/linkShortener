#!/bin/bash

# Генерируем ключ приложения
php artisan key:generate --force

# Запускаем миграции
php artisan migrate --force

# Оптимизируем Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Запускаем supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
