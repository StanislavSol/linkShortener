install:
	cp -n .env.example .env || true
	composer install
	php artisan key:generate
	touch database/database.sqlite
	php artisan migrate --seed
	npm install
	npm run build

fresh:
	php artisan migrate:fresh --seed

seed:
	php artisan db:seed

start:
	php artisan serve --host 0.0.0.0

console:
	php artisan tinker
