start:
	php artisan serve --host 0.0.0.0

setup:
	composer install
	cp -n .env.example .env
	php artisan key:gen --ansi
	touch database/database.sqlite
	php artisan migrate
	php artisan db:seed
	npm ci

lint:
	composer exec --verbose phpcs -- --standard=PSR12 app/Http/Controllers/UrlController.php routes tests/Feature/UrlControllerTest.php

test:
	php artisan test

test-coverage-text:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-text

test-coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml

deploy:
	git push heroku main
