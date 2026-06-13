.PHONY: up down build shell artisan test migrate fresh seed module filament-user lint fix analyse

up:
	docker compose up -d --build --remove-orphans

down:
	docker compose down --remove-orphans

build:
	docker compose build

shell:
	docker compose exec app sh

artisan:
	docker compose exec app php artisan $(filter-out $@,$(MAKECMDGOALS))

test:
	docker compose exec app php artisan test

lint:
	docker compose exec app composer lint

fix:
	docker compose exec app composer fix

analyse:
	docker compose exec app composer analyse

migrate:
	docker compose exec app php artisan migrate

fresh:
	docker compose exec app php artisan migrate:fresh --seed

seed:
	docker compose exec app php artisan db:seed

module:
	docker compose exec app php artisan module:make $(filter-out $@,$(MAKECMDGOALS))

filament-user:
	docker compose exec app php artisan make:filament-user

%:
	@:
