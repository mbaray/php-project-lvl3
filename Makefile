install:
	composer install

lint:
	composer exec --verbose phpcs -- --standard=PSR12 Makefile

start:
	php artisan serve --host 0.0.0.0

deploy:
	git push heroku main
