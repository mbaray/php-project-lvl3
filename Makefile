install:
	composer install

setup:
    cp -n .env.example .env
    php artisan key:gen --ansi
    touch database/database.sqlite
    php artisan migrate
    php artisan db:seed
    npm ci
    npm run dev

lint:
	composer exec --verbose phpcs -- --standard=PSR12 app/Http/Controllers/UrlController.php

start:
	php artisan serve --host 0.0.0.0

test:
	php artisan test

deploy:
	git push heroku main
