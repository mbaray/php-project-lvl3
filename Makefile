install:
	composer install

setup:
	composer install
	cp -n .env.example .env
	php artisan key:gen --ansi
	touch database/database.pgsql
	php artisan migrate
	php artisan db:seed
	npm ci
	npm run dev
	make ide-helper

lint:
	composer exec --verbose phpcs -- --standard=PSR12 app/Http/Controllers/UrlController.php

start:
	php artisan serve --host 0.0.0.0

test:
	php artisan test

deploy:
	git push heroku main
