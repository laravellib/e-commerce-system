#---------------------------
# Docker controls
#---------------------------

up:
	docker-compose up -d

down:
	docker-compose down

s:
	docker-compose ps

restart:
	docker-compose down
	docker-compose up -d

build:
	docker-compose up -d --build

rebuild:
	docker-compose build --no-cache

remove-volumes:
	docker-compose down --volumes

#---------------------------
# Application
#---------------------------

migrate:
	docker-compose exec php-cli php artisan migrate

rollback:
	docker-compose exec php-cli php artisan rollback

refresh:
	docker-compose exec php-cli php artisan migrate:refresh

seed:
	docker-compose exec php-cli php artisan db:seed

reseed: refresh seed

tinker:
	docker-compose exec php-cli php artisan tinker

test:
	docker-compose exec php-cli vendor/bin/phpunit

coverage:
	docker-compose exec php-cli vendor/bin/phpunit --coverage-html tests/report

autoload:
	docker-compose exec php-cli composer dump-autoload

perm:
	sudo chmod -R 777 bootstrap/cache
	sudo chmod -R 777 storage

env:
	cp ./.env.example ./.env
	docker-compose exec php-cli php artisan key:generate

#---------------------------
# Front-end
#---------------------------

assets:
	docker-compose exec node yarn install

watch:
	docker-compose exec node yarn watch
