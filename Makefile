install:
	composer install

lint:
	composer exec --verbose phpcs -- --standard=PSR12 app/Http/Controllers/UrlController.php

start:
	php artisan serve --host 0.0.0.0

deploy:
	git push heroku main
